<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('solicitudes', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_seguimiento')->unique();

            // Tipo de certificado solicitado
            $table->enum('tipo_certificado', ['anexo_14', 'anexo_13', 'evento_publico']);

            // Datos del solicitante
            $table->string('nombres_solicitante');
            $table->string('dni_ruc');
            $table->string('telefono_whatsapp');
            $table->string('email')->nullable();

            // Datos del establecimiento/evento
            $table->string('nombre_comercial')->nullable();
            $table->string('nombre_evento')->nullable();
            $table->string('direccion');
            $table->string('provincia')->default('HUAMANGA');
            $table->string('departamento')->default('AYACUCHO');
            $table->string('actividad')->nullable();
            $table->decimal('area_edificacion', 8, 2)->nullable();

            // Datos evento público
            $table->date('fecha_evento')->nullable();
            $table->string('organizador_nombre')->nullable();
            $table->string('organizador_dni')->nullable();

            // Estado del trámite
            $table->enum('estado', ['recibido', 'en_revision', 'aprobado', 'rechazado'])
                ->default('recibido');
            $table->text('observaciones')->nullable();

            // Documentos adjuntos
            $table->string('doc_solicitud')->nullable();
            $table->string('doc_plano')->nullable();
            $table->string('doc_otros')->nullable();

            // Relación con licencia generada
            $table->foreignId('licencia_id')->nullable()->constrained('licencias')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitudes');
    }
};