<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('requisitos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('licencia_id')->constrained('licencias');
            $table->string('nombre_documento');
            $table->enum('estado', ['pendiente', 'presentado', 'observado'])->default('pendiente');
            $table->string('archivo')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('requisitos');
    }
};