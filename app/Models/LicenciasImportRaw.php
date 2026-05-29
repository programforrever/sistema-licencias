<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LicenciasImportRaw extends Model
{
    protected $table = 'licencias_import_raw';

    protected $fillable = [
        'mes',
        'anexo',
        'numero_licencia',
        'fecha_emision',
        'fecha_fin_evento',
        'numero_expediente',
        'actividad',
        'nombre_comercial',
        'solicitante',
        'ubicacion',
        'tipo',
        'estatus_procesamiento',
        'notas_error',
    ];

    protected $casts = [
        'fecha_emision' => 'datetime',
        'fecha_fin_evento' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
