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
            if (!Schema::hasColumn('licencias', 'dias_evento')) {
                $table->integer('dias_evento')->default(1)->after('fecha_evento')->comment('Número de días de vigencia del evento');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('licencias', function (Blueprint $table) {
            if (Schema::hasColumn('licencias', 'dias_evento')) {
                $table->dropColumn('dias_evento');
            }
        });
    }
};
