<?php
namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use PDO;
use PharData;
use Tests\TestCase;

class LocalBackupTest extends TestCase
{
    public function test_sqlite_backup_restores_rows_and_photos_without_secrets_or_old_backups(): void
    {
        $temporary = sys_get_temp_dir().DIRECTORY_SEPARATOR.'terminales-backup-test-'.bin2hex(random_bytes(6));
        $originalStorage = storage_path();
        mkdir($temporary, 0700);
        $this->app->useStoragePath($temporary);
        config(['database.default' => 'backup_test', 'database.connections.backup_test' => ['driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '']]);
        try {
            DB::statement('CREATE TABLE buses (id INTEGER PRIMARY KEY, nombre TEXT, tarifa NUMERIC)');
            DB::table('buses')->insert(['id' => 1, 'nombre' => 'Ruta de prueba', 'tarifa' => 25.50]);
            File::ensureDirectoryExists(storage_path('app/public/imagenes'));
            File::ensureDirectoryExists(storage_path('app/sugerencias-terminales'));
            File::ensureDirectoryExists(storage_path('app/backups'));
            file_put_contents(storage_path('app/public/imagenes/foto.jpg'), 'fake-photo-bytes');
            file_put_contents(storage_path('app/sugerencias-terminales/privada.jpg'), 'private-photo');
            file_put_contents(storage_path('app/public/.env'), 'SECRET=must-not-archive');
            file_put_contents(storage_path('app/backups/old.zip'), 'old');
            $this->artisan('terminales:backup')->assertSuccessful();
            $archives = glob(storage_path('app/backups/terminales-*.zip'));
            $this->assertCount(1, $archives);
            $archive = new PharData($archives[0]);
            $manifest = json_decode($archive['manifest.json']->getContent(), true);
            $restored = $temporary.DIRECTORY_SEPARATOR.'restored.sqlite';
            file_put_contents($restored, $archive['database.sqlite']->getContent());
            $pdo = new PDO('sqlite:'.$restored);
            $row = $pdo->query('SELECT * FROM buses')->fetch(PDO::FETCH_ASSOC);
            $this->assertSame('Ruta de prueba', $row['nombre']);
            $this->assertEquals(25.50, $row['tarifa']);
            $this->assertSame($manifest['database_sha256'], hash_file('sha256', $restored));
            $this->assertSame('fake-photo-bytes', $archive['storage/app/public/imagenes/foto.jpg']->getContent());
            $this->assertSame('private-photo', $archive['storage/app/sugerencias-terminales/privada.jpg']->getContent());
            $this->assertFalse(isset($archive['storage/app/public/.env']));
            $this->assertFalse(isset($archive['storage/app/backups/old.zip']));
            $this->assertSame(2, $manifest['photo_files']);
            $this->assertCount(2, File::files(storage_path('app/backups')));
            $this->assertCount(0, File::directories(storage_path('app/backups')));
            unset($archive, $pdo);
        } finally {
            DB::purge('backup_test');
            $this->app->useStoragePath($originalStorage);
            File::deleteDirectory($temporary);
        }
    }

    public function test_failure_is_redacted_and_temporary_directory_is_removed(): void
    {
        $temporary = sys_get_temp_dir().DIRECTORY_SEPARATOR.'terminales-backup-test-'.bin2hex(random_bytes(6));
        $originalStorage = storage_path();
        mkdir($temporary, 0700);
        $this->app->useStoragePath($temporary);
        config(['database.default' => 'missing-secret-connection']);
        try {
            $this->artisan('terminales:backup')->expectsOutputToContain('No se pudo crear el respaldo.')->doesntExpectOutputToContain('missing-secret-connection')->assertFailed();
            $this->assertCount(0, File::allFiles(storage_path('app/backups')));
            $this->assertCount(0, File::directories(storage_path('app/backups')));
        } finally {
            $this->app->useStoragePath($originalStorage);
            File::deleteDirectory($temporary);
        }
    }
}
