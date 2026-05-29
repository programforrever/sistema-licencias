<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('licencias', function (Blueprint $table) {
            // Cambiar el enum de signature_status para agregar 'firmado_adjunto'
            DB::statement("ALTER TABLE licencias MODIFY COLUMN signature_status ENUM('pendiente', 'pendiente_firma', 'firmado', 'firmado_adjunto') DEFAULT 'pendiente'");
        });
    }

    public function down(): void
    {
        Schema::table('licencias', function (Blueprint $table) {
            // Revertir al enum original
            DB::statement("ALTER TABLE licencias MODIFY COLUMN signature_status ENUM('pendiente_firma', 'firmado') DEFAULT 'pendiente_firma'");
        });
    }
};
