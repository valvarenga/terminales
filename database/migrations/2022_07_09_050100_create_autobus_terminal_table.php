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
        Schema::create('autobus_terminal', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('autobus_id');
            $table->unsignedBigInteger('terminal_id');

            $table->foreign('autobus_id')->references('id')->on('autobuses')->onDelete('cascade');
            $table->foreign('terminal_id')->references('id')->on('terminales')->onDelete('cascade');
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
        Schema::dropIfExists('autobus_terminal');
    }
};
