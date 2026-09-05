<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sugerencias_terminales', function (Blueprint $table) {
            $table->string('estado', 20)->default('pendiente')->index();
            $table->text('motivo_revision')->nullable();
            $table->string('revisada_por')->nullable();
            $table->timestamp('revisada_at')->nullable();
            $table->foreignId('terminal_id')->nullable()->constrained('terminales')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('sugerencias_terminales', function (Blueprint $table) {
            $table->dropForeign(['terminal_id']);
            $table->dropColumn(['estado', 'motivo_revision', 'revisada_por', 'revisada_at', 'terminal_id']);
        });
    }
};
