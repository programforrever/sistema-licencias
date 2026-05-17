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
        Schema::table('solicitudes', function (Blueprint $table) {
            // Agregar campo de monto pagado si no existe
            if (!Schema::hasColumn('solicitudes', 'monto_pago')) {
                $table->decimal('monto_pago', 10, 2)->nullable()->after('estado_pago')->comment('Monto pagado por la solicitud');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('solicitudes', function (Blueprint $table) {
            if (Schema::hasColumn('solicitudes', 'monto_pago')) {
                $table->dropColumn('monto_pago');
            }
        });
    }
};
