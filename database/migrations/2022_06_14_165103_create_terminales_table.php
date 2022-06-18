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
        Schema::create('terminales', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->time('hora_apertura');
            $table->time('hora_cierre');
            $table->unsignedBigInteger('departamento_id');
            $table->unsignedBigInteger('municipio_id');
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
        Schema::dropIfExists('terminales');
    }
};
