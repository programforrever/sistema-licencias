<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::dropIfExists('contribuyentes');
        Schema::create('contribuyentes', function (Blueprint $table) {
            $table->id();
            $table->string('dni_ruc', 20)->unique();
            $table->enum('tipo_persona', ['natural', 'juridica']);
            $table->string('nombres_razon_social');
            $table->string('direccion');
            $table->string('telefono', 20)->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('contribuyentes');
    }
};