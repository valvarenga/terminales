<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('autobuses', fn (Blueprint $table) => $table->index(
            ['municipio_origen_id', 'hora_salida'],
            'autobuses_origen_salida_index'
        ));
    }

    public function down(): void
    {
        Schema::table('autobuses', fn (Blueprint $table) => $table->dropIndex('autobuses_origen_salida_index'));
    }
};
