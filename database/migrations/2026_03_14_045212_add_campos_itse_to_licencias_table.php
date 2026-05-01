<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('licencias', function (Blueprint $table) {
            // Tipo de certificado
            $table->enum('tipo_certificado', ['anexo_14', 'anexo_13', 'evento_publico'])
                ->default('anexo_14')->after('estado');

            // Campos comunes Anexo 13 y 14
            $table->integer('capacidad_maxima')->nullable()->after('tipo_certificado');
            $table->string('capacidad_letras')->nullable()->after('capacidad_maxima');
            $table->decimal('area_edificacion', 8, 2)->nullable()->after('capacidad_letras');
            $table->string('numero_expediente')->nullable()->after('area_edificacion');
            $table->string('informe_aprobacion')->nullable()->after('numero_expediente');
            $table->string('vigencia')->nullable()->after('informe_aprobacion');
            $table->string('provincia')->nullable()->after('vigencia');
            $table->string('departamento')->nullable()->after('provincia');
            $table->string('solicitado_por')->nullable()->after('departamento');

            // Campos exclusivos Evento Público
            $table->string('nombre_establecimiento')->nullable()->after('solicitado_por');
            $table->string('nombre_evento')->nullable()->after('nombre_establecimiento');
            $table->date('fecha_evento')->nullable()->after('nombre_evento');
            $table->string('organizador_nombre')->nullable()->after('fecha_evento');
            $table->string('organizador_dni')->nullable()->after('organizador_nombre');
            $table->string('representante_legal')->nullable()->after('organizador_dni');
            $table->string('empresa_organizadora')->nullable()->after('representante_legal');
            $table->string('numero_informe_ecse')->nullable()->after('empresa_organizadora');
            $table->time('horario_inicio')->nullable()->after('numero_informe_ecse');
            $table->time('horario_fin')->nullable()->after('horario_inicio');
            $table->text('restricciones')->nullable()->after('horario_fin');
        });
    }

    public function down(): void
    {
        Schema::table('licencias', function (Blueprint $table) {
            $table->dropColumn([
                'tipo_certificado', 'capacidad_maxima', 'capacidad_letras',
                'area_edificacion', 'numero_expediente', 'informe_aprobacion',
                'vigencia', 'provincia', 'departamento', 'solicitado_por',
                'nombre_establecimiento', 'nombre_evento', 'fecha_evento',
                'organizador_nombre', 'organizador_dni', 'representante_legal',
                'empresa_organizadora', 'numero_informe_ecse',
                'horario_inicio', 'horario_fin', 'restricciones'
            ]);
        });
    }
};