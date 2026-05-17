<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('revisiones_solicitud', function (Blueprint $table) {
            $table->id();
            $table->foreignId('solicitud_id')->constrained('solicitudes')->cascadeOnDelete();
            $table->foreignId('revisor_solicitud_id')->constrained('revisores_solicitud')->cascadeOnDelete();
            $table->text('notas')->nullable(); // Notas del revisor
            $table->string('documento_revision')->nullable(); // Documento adjuntado por revisor
            $table->enum('resultado_revision', ['aprobado', 'requiere_cambios', 'rechazado'])->nullable();
            $table->timestamp('entregado_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('revisiones_solicitud');
    }
};
