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
        Schema::create('licencias_historicas', function (Blueprint $table) {
            $table->id();
            
            // Datos principales
            $table->string('numero_licencia')->unique();
            $table->enum('tipo_certificado', ['anexo_13', 'anexo_14', 'evento_publico']);
            $table->date('fecha_emision');
            $table->integer('vigencia')->default(2)->comment('Años de vigencia (2 para ITSE)');
            
            // Estado calculado
            $table->enum('estado', ['vigente', 'vencido', 'sin_vencimiento'])->default('vigente');
            
            // Datos del solicitante
            $table->string('solicitante');
            $table->text('ubicacion')->nullable();
            $table->string('nombre_comercial')->nullable();
            
            // Datos administrativos
            $table->string('actividad')->nullable();
            $table->string('numero_expediente')->nullable();
            $table->string('informe_numero')->nullable();
            
            // Timestamps
            $table->timestamps();
            
            // Índices
            $table->index('tipo_certificado');
            $table->index('estado');
            $table->index('fecha_emision');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('licencias_historicas');
    }
};
