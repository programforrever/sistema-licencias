<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('licencias_import_raw', function (Blueprint $table) {
            $table->date('fecha_fin_evento')->nullable()->after('fecha_emision')->comment('Fecha de fin del evento (para ECSE)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('licencias_import_raw', function (Blueprint $table) {
            $table->dropColumn('fecha_fin_evento');
        });
    }
};
