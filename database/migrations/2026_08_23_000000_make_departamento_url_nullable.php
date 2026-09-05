<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        \Illuminate\Support\Facades\Schema::table('departamentos', fn ($table) => $table->longText('url')->nullable()->change());
    }

    public function down()
    {
        \Illuminate\Support\Facades\Schema::table('departamentos', fn ($table) => $table->longText('url')->nullable(false)->change());
    }
};
