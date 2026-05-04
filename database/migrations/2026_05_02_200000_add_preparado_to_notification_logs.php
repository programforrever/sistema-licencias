<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modificar el enum para incluir 'preparado'
        if (Schema::hasTable('notification_logs')) {
            DB::statement("ALTER TABLE notification_logs MODIFY estado ENUM('enviado', 'falló', 'preparado') DEFAULT 'enviado'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('notification_logs')) {
            DB::statement("ALTER TABLE notification_logs MODIFY estado ENUM('enviado', 'falló') DEFAULT 'enviado'");
        }
    }
};
