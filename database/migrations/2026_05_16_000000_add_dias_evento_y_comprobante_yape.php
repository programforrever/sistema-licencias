<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('solicitudes', function (Blueprint $table) {
            // Agregar campo de días para eventos
            if (!Schema::hasColumn('solicitudes', 'dias_evento')) {
                $table->integer('dias_evento')->default(1)->after('fecha_evento')->comment('Número de días del evento para validez');
            }
            
            // Agregar campo para comprobante de Yape pagado
            if (!Schema::hasColumn('solicitudes', 'doc_comprobante_yape')) {
                $table->string('doc_comprobante_yape')->nullable()->after('doc_comprobante_pago')->comment('Comprobante del pago realizado por Yape para eventos');
            }
        });
    }

    public function down(): void
    {
        Schema::table('solicitudes', function (Blueprint $table) {
            if (Schema::hasColumn('solicitudes', 'dias_evento')) {
                $table->dropColumn('dias_evento');
            }
            if (Schema::hasColumn('solicitudes', 'doc_comprobante_yape')) {
                $table->dropColumn('doc_comprobante_yape');
            }
        });
    }
};
