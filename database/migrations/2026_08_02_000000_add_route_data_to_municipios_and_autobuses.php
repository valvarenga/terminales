<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('municipios', function (Blueprint $table) {
            $table->decimal('latitud', 10, 7)->nullable()->after('departamento_id');
            $table->decimal('longitud', 10, 7)->nullable()->after('latitud');
        });

        Schema::table('autobuses', function (Blueprint $table) {
            $table->foreignId('municipio_origen_id')->nullable()->after('origen')
                ->constrained('municipios')->nullOnDelete();
            $table->foreignId('municipio_destino_id')->nullable()->after('destino')
                ->constrained('municipios')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('autobuses', function (Blueprint $table) {
            $table->dropForeign(['municipio_origen_id']);
            $table->dropForeign(['municipio_destino_id']);
            $table->dropColumn(['municipio_origen_id', 'municipio_destino_id']);
        });

        Schema::table('municipios', function (Blueprint $table) {
            $table->dropColumn(['latitud', 'longitud']);
        });
    }
};
