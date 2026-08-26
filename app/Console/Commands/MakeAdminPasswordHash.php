<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class MakeAdminPasswordHash extends Command
{
    protected $signature = 'admin:password-hash';

    protected $description = 'Generate a secure hash for ADMIN_PASSWORD_HASH';

    public function handle(): int
    {
        $password = $this->secret('Nueva clave del administrador');

        if (! is_string($password) || $password === '') {
            $this->error('La clave no puede estar vacia.');

            return self::FAILURE;
        }

        $this->line(Hash::make($password));
        $this->newLine();
        $this->comment('Copie el valor anterior en ADMIN_PASSWORD_HASH dentro del archivo .env del servidor.');

        return self::SUCCESS;
    }
}
