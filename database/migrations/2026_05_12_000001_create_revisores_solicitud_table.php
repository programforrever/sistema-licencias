<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('revisores_solicitud', function (Blueprint $table) {
            $table->id();
            $table->foreignId('solicitud_id')->constrained('solicitudes')->cascadeOnDelete();
            $table->string('email');
            $table->string('nombre_revisor');
            $table->enum('estado_revision', ['pendiente', 'revisado', 'rechazado'])->default('pendiente');
            $table->string('token_revisor')->unique(); // Token único para cada revisor
            $table->timestamp('enviado_at')->nullable();
            $table->timestamp('revisado_at')->nullable();
            $table->timestamps();

            $table->unique(['solicitud_id', 'email']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('revisores_solicitud');
    }
};
