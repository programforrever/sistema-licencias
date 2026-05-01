<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    protected $table = 'solicitudes';

    protected $fillable = [
        'codigo_seguimiento',
        'tipo_certificado',
        'nombres_solicitante',
        'dni_ruc',
        'telefono_whatsapp',
        'email',
        'nombre_comercial',
        'nombre_evento',
        'direccion',
        'provincia',
        'departamento',
        'actividad',
        'area_edificacion',
        'fecha_evento',
        'organizador_nombre',
        'organizador_dni',
        'estado',
        'observaciones',
        'doc_solicitud',
        'doc_plano',
        'doc_otros',
        'licencia_id',
    ];

    protected $casts = [
        'fecha_evento' => 'date',
    ];

    public function licencia()
    {
        return $this->belongsTo(Licencia::class);
    }
}