<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('licencias', function (Blueprint $table) {
            $table->id();
            $table->string('numero_licencia')->unique();
            $table->foreignId('contribuyente_id')->constrained('contribuyentes');
            $table->foreignId('actividad_economica_id')->constrained('actividades_economicas');
            $table->string('nombre_comercial');
            $table->string('direccion_establecimiento');
            $table->enum('estado', ['pendiente', 'aprobado', 'rechazado', 'suspendido'])->default('pendiente');
            $table->date('fecha_emision')->nullable();
            $table->date('fecha_vencimiento')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('licencias');
    }
};