<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('licencias', function (Blueprint $table) {
            $table->string('nombre_comercial')->nullable()->change();
            $table->string('direccion_establecimiento')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('licencias', function (Blueprint $table) {
            $table->string('nombre_comercial')->nullable(false)->change();
            $table->string('direccion_establecimiento')->nullable(false)->change();
        });
    }
};