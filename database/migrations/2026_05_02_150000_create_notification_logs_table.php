<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('solicitud_id')->nullable()->constrained('solicitudes')->onDelete('cascade');
            $table->enum('canal', ['email', 'whatsapp'])->index();
            $table->string('destinatario'); // Email o teléfono
            $table->text('mensaje')->nullable();
            $table->enum('estado', ['enviado', 'falló', 'preparado'])->default('enviado');
            $table->text('error_message')->nullable();
            $table->string('cambio_estado')->nullable(); // El estado que se notificó
            $table->timestamps();

            $table->index(['solicitud_id', 'canal']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_logs');
    }
};
