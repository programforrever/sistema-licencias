<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Licencia extends Model
{
    protected $fillable = [
        'numero_licencia',
        'contribuyente_id',
        'actividad_economica_id',
        'tipo_certificado',
        'estado',
        'fecha_emision',
        'fecha_vencimiento',
        'observaciones',
        // Comunes Anexo 13 y 14
        'nombre_comercial',
        'direccion_establecimiento',
        'capacidad_maxima',
        'capacidad_letras',
        'area_edificacion',
        'numero_expediente',
        'informe_aprobacion',
        'vigencia',
        'provincia',
        'departamento',
        'solicitado_por',
        // Exclusivos Evento Público
        'nombre_establecimiento',
        'nombre_evento',
        'fecha_evento',
        'organizador_nombre',
        'organizador_dni',
        'representante_legal',
        'empresa_organizadora',
        'numero_informe_ecse',
        'horario_inicio',
        'horario_fin',
        'restricciones',
    ];

    public function contribuyente()
    {
        return $this->belongsTo(Contribuyente::class);
    }

    public function actividadEconomica()
    {
        return $this->belongsTo(ActividadEconomica::class);
    }

    public function requisitos()
    {
        return $this->hasMany(Requisito::class);
    }
}