<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('solicitudes', function (Blueprint $table) {
            // Agregar nuevos campos de documentos
            $table->string('doc_dni_copia')->nullable()->after('doc_otros');
            $table->string('doc_comprobante_pago')->nullable()->after('doc_dni_copia');
        });
    }

    public function down(): void
    {
        Schema::table('solicitudes', function (Blueprint $table) {
            $table->dropColumn('doc_dni_copia');
            $table->dropColumn('doc_comprobante_pago');
        });
    }
};
