<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement('ALTER TABLE departamentos MODIFY url LONGTEXT NULL');
    }

    public function down()
    {
        DB::statement('ALTER TABLE departamentos MODIFY url LONGTEXT NOT NULL');
    }
};
