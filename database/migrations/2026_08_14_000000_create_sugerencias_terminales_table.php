<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sugerencias_terminales', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_terminal');
            $table->string('ubicacion')->nullable();
            $table->string('foto');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sugerencias_terminales');
    }
};
