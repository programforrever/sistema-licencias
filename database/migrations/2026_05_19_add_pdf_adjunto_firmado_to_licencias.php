<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('licencias', function (Blueprint $table) {
            if (!Schema::hasColumn('licencias', 'pdf_adjunto_firmado_path')) {
                $table->string('pdf_adjunto_firmado_path')->nullable()->after('pdf_firmado_path')
                    ->comment('PDF ya firmado adjuntado por el usuario');
            }
            if (!Schema::hasColumn('licencias', 'firmado_adjunto_at')) {
                $table->timestamp('firmado_adjunto_at')->nullable()->after('signed_at')
                    ->comment('Fecha y hora cuando se adjuntó el PDF firmado');
            }
        });
    }

    public function down(): void
    {
        Schema::table('licencias', function (Blueprint $table) {
            if (Schema::hasColumn('licencias', 'pdf_adjunto_firmado_path')) {
                $table->dropColumn('pdf_adjunto_firmado_path');
            }
            if (Schema::hasColumn('licencias', 'firmado_adjunto_at')) {
                $table->dropColumn('firmado_adjunto_at');
            }
        });
    }
};
