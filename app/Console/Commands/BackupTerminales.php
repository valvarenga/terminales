<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Phar;
use PharData;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Symfony\Component\Process\Process;
use Throwable;

class BackupTerminales extends Command
{
    protected $signature = 'terminales:backup';
    protected $description = 'Respalda la base de datos y fotos en un ZIP local privado';

    public function handle(): int
    {
        $root = storage_path('app/backups');
        $work = null;
        $archivePath = null;
        $archive = null;
        try {
            if (!class_exists(PharData::class)) {
                throw new \RuntimeException('Phar unavailable');
            }
            if (!is_dir($root) && !mkdir($root, 0700, true)) {
                throw new \RuntimeException('Cannot create backup directory');
            }
            if (is_link($root)) {
                throw new \RuntimeException('Backup directory cannot be a symlink');
            }
            chmod($root, 0700);
            $name = 'terminales-'.gmdate('Ymd-His').'-'.bin2hex(random_bytes(6));
            $work = $root.DIRECTORY_SEPARATOR.'.'.$name;
            if (!mkdir($work, 0700)) {
                throw new \RuntimeException('Cannot create workspace');
            }
            $connection = DB::connection();
            $driver = $connection->getDriverName();
            $dumpName = $driver === 'sqlite' ? 'database.sqlite' : 'database.sql';
            $dump = $work.DIRECTORY_SEPARATOR.$dumpName;
            if ($driver === 'sqlite') {
                $pdo = $connection->getPdo();
                // VACUUM INTO produces a consistent standalone SQLite snapshot, including WAL data.
                $pdo->exec('VACUUM INTO '.$pdo->quote($dump));
            } elseif (in_array($driver, ['mysql', 'mariadb'], true)) {
                $this->dumpMysql($connection->getConfig(), $work, $dump);
            } else {
                throw new \RuntimeException('Unsupported database');
            }
            chmod($dump, 0600);
            $archivePath = $root.DIRECTORY_SEPARATOR.$name.'.zip';
            $archive = new PharData($archivePath, 0, null, Phar::ZIP);
            $archive->addFile($dump, $dumpName);
            $files = 0;
            // Explicit allowlist excludes .env, existing backups, sessions, logs and arbitrary app files.
            foreach (['public', 'sugerencias-terminales'] as $folder) {
                $source = storage_path('app/'.$folder);
                if (!is_dir($source) || is_link($source)) {
                    continue;
                }
                $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS));
                foreach ($iterator as $file) {
                    if (!$file->isFile() || $file->isLink() || str_starts_with($file->getFilename(), '.')) {
                        continue;
                    }
                    $relative = str_replace(DIRECTORY_SEPARATOR, '/', substr($file->getPathname(), strlen($source) + 1));
                    if (preg_match('~(^|/)\.[^/]*(/|$)~', $relative)) {
                        continue;
                    }
                    $archive->addFile($file->getPathname(), 'storage/app/'.$folder.'/'.$relative);
                    $files++;
                }
            }
            $archive->addFromString('manifest.json', json_encode([
                'format' => 1, 'created_at' => gmdate(DATE_ATOM), 'database_driver' => $driver,
                'database_file' => $dumpName, 'database_sha256' => hash_file('sha256', $dump), 'photo_files' => $files,
            ], JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR));
            unset($archive);
            $archive = null;
            chmod($archivePath, 0600);
            $this->info('Respaldo creado: storage/app/backups/'.basename($archivePath));
            return self::SUCCESS;
        } catch (Throwable $exception) {
            unset($archive);
            if ($archivePath && is_file($archivePath)) {
                unlink($archivePath);
            }
            // Process and DB exceptions may contain credentials. Never print or log their text.
            $this->error('No se pudo crear el respaldo. Revise permisos, espacio disponible, conexion y ejecutable mysqldump; SQLite requiere no tener una transaccion abierta.');
            return self::FAILURE;
        } finally {
            if ($work && is_dir($work)) {
                foreach (glob($work.DIRECTORY_SEPARATOR.'*') ?: [] as $temporary) {
                    if (is_file($temporary)) {
                        unlink($temporary);
                    }
                }
                rmdir($work);
            }
        }
    }

    private function dumpMysql(array $database, string $work, string $dump): void
    {
        $options = ['host' => $database['host'] ?? 'localhost', 'port' => $database['port'] ?? 3306,
            'user' => $database['username'] ?? '', 'password' => $database['password'] ?? ''];
        if (!empty($database['unix_socket'])) {
            $options['socket'] = $database['unix_socket'];
        }
        if (!empty($database['options'][\PDO::MYSQL_ATTR_SSL_CA])) {
            $options['ssl-ca'] = $database['options'][\PDO::MYSQL_ATTR_SSL_CA];
        }
        $content = "[client]\n";
        foreach ($options as $key => $value) {
            $value = str_replace(["\\", '"', "\n", "\r"], ["\\\\", '\\"', '\\n', '\\r'], (string) $value);
            $content .= $key.'="'.$value.'"'."\n";
        }
        $defaults = $work.DIRECTORY_SEPARATOR.'client.cnf';
        $handle = fopen($defaults, 'x');
        if (!$handle) {
            throw new \RuntimeException('Cannot write client configuration');
        }
        chmod($defaults, 0600);
        try {
            fwrite($handle, $content);
        } finally {
            fclose($handle);
        }
        $process = new Process([
            config('backups.mysqldump', 'mysqldump'), '--defaults-file='.$defaults,
            '--single-transaction', '--quick', '--skip-lock-tables', '--hex-blob',
            '--result-file='.$dump, '--databases', $database['database'],
        ], null, ['MYSQL_PWD' => false]);
        $process->setTimeout((float) config('backups.timeout', 300));
        $process->disableOutput();
        $process->mustRun();
    }
}
