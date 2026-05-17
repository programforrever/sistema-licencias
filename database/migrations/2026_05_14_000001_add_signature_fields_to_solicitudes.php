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
        Schema::table('licencias', function (Blueprint $table) {
            if (!Schema::hasColumn('licencias', 'signature_status')) {
                $table->enum('signature_status', ['pendiente_firma', 'firmado'])
                    ->default('pendiente_firma')
                    ->after('estado')
                    ->comment('Estado de firma digital del certificado');
            }
            if (!Schema::hasColumn('licencias', 'pdf_path')) {
                $table->string('pdf_path')->nullable()->after('signature_status')
                    ->comment('Ruta PDF original sin firmar');
            }
            if (!Schema::hasColumn('licencias', 'pdf_firmado_path')) {
                $table->string('pdf_firmado_path')->nullable()->after('pdf_path')
                    ->comment('Ruta PDF con firma incrustada');
            }
            if (!Schema::hasColumn('licencias', 'signed_by_user_id')) {
                $table->foreignId('signed_by_user_id')->nullable()->after('pdf_firmado_path')
                    ->constrained('users')->onDelete('set null')
                    ->comment('ID del usuario que realizó la firma');
            }
            if (!Schema::hasColumn('licencias', 'signed_at')) {
                $table->timestamp('signed_at')->nullable()->after('signed_by_user_id')
                    ->comment('Fecha/hora de cuando se firmó el certificado');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('licencias', function (Blueprint $table) {
            if (Schema::hasColumn('licencias', 'signed_at')) {
                $table->dropColumn('signed_at');
            }
            if (Schema::hasColumn('licencias', 'signed_by_user_id')) {
                $table->dropForeignKeyConstraints();
                $table->dropColumn('signed_by_user_id');
            }
            if (Schema::hasColumn('licencias', 'pdf_firmado_path')) {
                $table->dropColumn('pdf_firmado_path');
            }
            if (Schema::hasColumn('licencias', 'pdf_path')) {
                $table->dropColumn('pdf_path');
            }
            if (Schema::hasColumn('licencias', 'signature_status')) {
                $table->dropColumn('signature_status');
            }
        });
    }
};

