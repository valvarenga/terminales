<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('autobus_paradas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('autobus_id')->constrained('autobuses')->cascadeOnDelete();
            $table->foreignId('municipio_id')->constrained('municipios')->restrictOnDelete();
            $table->unsignedSmallInteger('posicion');
            $table->time('hora_paso');
            $table->decimal('tarifa_acumulada', 8, 2)->nullable();
            $table->timestamps();
            $table->unique(['autobus_id', 'posicion']);
            $table->unique(['autobus_id', 'municipio_id']);
            $table->index(['municipio_id', 'hora_paso']);
        });

        DB::table('autobuses')
            ->whereNotNull('municipio_origen_id')
            ->whereNotNull('municipio_destino_id')
            ->orderBy('id')
            ->each(function ($bus) {
                $now = now();
                DB::table('autobus_paradas')->insert([
                    ['autobus_id' => $bus->id, 'municipio_id' => $bus->municipio_origen_id, 'posicion' => 0, 'hora_paso' => $bus->hora_salida, 'tarifa_acumulada' => 0, 'created_at' => $now, 'updated_at' => $now],
                    ['autobus_id' => $bus->id, 'municipio_id' => $bus->municipio_destino_id, 'posicion' => 1, 'hora_paso' => $bus->hora_llegada, 'tarifa_acumulada' => $bus->tarifa, 'created_at' => $now, 'updated_at' => $now],
                ]);
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('autobus_paradas');
    }
};
