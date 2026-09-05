<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        \Illuminate\Support\Facades\Schema::table('municipios', fn ($table) => $table->longText('url_M')->nullable()->change());
    }

    public function down()
    {
        \Illuminate\Support\Facades\Schema::table('municipios', fn ($table) => $table->longText('url_M')->nullable(false)->change());
    }
};
