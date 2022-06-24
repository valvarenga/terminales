<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('autobuses', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->boolean('categoria')->nullable();
            $table->string('placa')->nullable();
            $table->string('origen');
            $table->time('hora_salida');
            $table->string('destino');
            $table->time('hora_llegada');
            $table->unsignedBigInteger('terminal_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('autobuses');
    }
};
