<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        \Illuminate\Support\Facades\Schema::table('terminales', fn ($table) => $table->longText('url_T')->nullable()->change());
    }

    public function down()
    {
        \Illuminate\Support\Facades\Schema::table('terminales', fn ($table) => $table->longText('url_T')->nullable(false)->change());
    }
};
