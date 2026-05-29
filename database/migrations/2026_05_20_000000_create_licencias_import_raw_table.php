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
        Schema::create('licencias_import_raw', function (Blueprint $table) {
            $table->id();
            $table->string('mes')->nullable();
            $table->string('anexo')->nullable();
            $table->string('numero_licencia')->nullable();
            $table->date('fecha_emision')->nullable();
            $table->string('numero_expediente')->nullable();
            $table->text('actividad')->nullable();
            $table->string('nombre_comercial')->nullable();
            $table->string('solicitante')->nullable();
            $table->string('ubicacion')->nullable();
            $table->enum('tipo', ['anexo_13', 'anexo_14', 'evento_publico']);
            $table->enum('estatus_procesamiento', ['pendiente', 'procesado', 'error'])->default('pendiente');
            $table->text('notas_error')->nullable();
            $table->timestamps();
            
            // Índices
            $table->index('tipo');
            $table->index('estatus_procesamiento');
            $table->index('numero_licencia');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('licencias_import_raw');
    }
};
