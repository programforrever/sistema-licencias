<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('solicitudes', function (Blueprint $table) {
            // Cambiar enum de estados: agregar 'registrado', 'aceptado', 'enviado_a_revision'
            // Primero actualizar valores existentes en la BD
            DB::statement("ALTER TABLE solicitudes MODIFY estado ENUM('registrado', 'aceptado', 'enviado_a_revision', 'recibido', 'en_revision', 'aprobado', 'rechazado') DEFAULT 'registrado'");
            
            // Agregar campo de estado de pago
            $table->enum('estado_pago', ['pago_pendiente', 'pago_validado', 'pago_rechazado'])
                ->nullable()
                ->after('estado');
            
            // Agregar token para acceso a revisión
            $table->string('token_revision')->nullable()->unique()->after('estado_pago');
        });
    }

    public function down(): void
    {
        Schema::table('solicitudes', function (Blueprint $table) {
            $table->dropColumn(['estado_pago', 'token_revision']);
        });
    }
};
