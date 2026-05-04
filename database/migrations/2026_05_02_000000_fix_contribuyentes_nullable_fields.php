<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contribuyentes', function (Blueprint $table) {
            // Hacer dni_ruc nullable para permitir contribuyentes sin documentación
            $table->string('dni_ruc', 20)->nullable()->change();
            
            // Agregar default a tipo_persona por si acaso
            $table->enum('tipo_persona', ['natural', 'juridica'])
                ->default('natural')
                ->change();
            
            //Hacer dirección nullable
            $table->string('direccion')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('contribuyentes', function (Blueprint $table) {
            $table->string('dni_ruc', 20)->nullable(false)->change();
            $table->enum('tipo_persona', ['natural', 'juridica'])
                ->default(null)
                ->change();
            $table->string('direccion')->nullable(false)->change();
        });
    }
};
