<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        \Illuminate\Support\Facades\Schema::table('sugerencias_terminales', fn ($table) => $table->string('foto')->nullable()->change());
    }

    public function down()
    {
        \Illuminate\Support\Facades\Schema::table('sugerencias_terminales', fn ($table) => $table->string('foto')->nullable(false)->change());
    }
};
