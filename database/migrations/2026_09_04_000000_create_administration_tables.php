<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Existing public accounts must not acquire administrative access.
            $table->string('role', 20)->default('none');
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('session_version')->default(1);
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('actor');
            $table->string('entity', 80)->index();
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->string('label');
            $table->string('action', 50)->index();
            $table->json('before')->nullable();
            $table->json('after')->nullable();
            $table->timestamp('created_at')->index();
        });

        Schema::create('route_searches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('origin_id')->index();
            $table->unsignedBigInteger('destination_id')->index();
            $table->string('origin_name');
            $table->string('destination_name');
            $table->unsignedInteger('result_count');
            $table->timestamp('created_at')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('route_searches');
        Schema::dropIfExists('audit_logs');
        Schema::table('users', fn (Blueprint $table) => $table->dropColumn(['role', 'is_active', 'session_version']));
    }
};
