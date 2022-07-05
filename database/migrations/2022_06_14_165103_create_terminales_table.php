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
            $table->string('slug');
            $table->time('hora_apertura');
            $table->time('hora_cierre');
            $table->unsignedBigInteger('departamento_id')->nullable();
            $table->unsignedBigInteger('municipio_id')->nullable();

            $table->foreign('departamento_id')
            ->references('id')->on('departamentos')->onDelete('set null');

            $table->foreign('municipio_id')
            ->references('id')->on('municipios')->onDelete('set null');
           
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
