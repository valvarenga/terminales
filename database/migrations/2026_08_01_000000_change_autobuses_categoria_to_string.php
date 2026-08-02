<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // La migración original definía categoria como boolean, pero la aplicación
        // guarda los valores "Expreso" y "Ruteado".
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE autobuses MODIFY categoria VARCHAR(255) NULL');
        }
    }

    public function down()
    {
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE autobuses MODIFY categoria TINYINT(1) NULL');
        }
    }
};
