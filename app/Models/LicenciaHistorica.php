<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class LicenciaHistorica extends Model
{
    protected $table = 'licencias_historicas';

    protected $fillable = [
        'numero_licencia',
        'tipo_certificado',      // 'anexo_13', 'anexo_14', 'evento_publico'
        'fecha_emision',
        'solicitante',
        'ubicacion',
        'nombre_comercial',
        'actividad',
        'numero_expediente',
        'informe_numero',
        'vigencia',              // En años (normalmente 2)
        'estado',                // 'vigente', 'vencido'
    ];

    protected $casts = [
        'fecha_emision' => 'date',
    ];

    /**
     * Calcular si la licencia está vigente o vencida
     * Válido solo para ITSE 13 y 14 (vigencia de 2 años)
     */
    public function calcularEstado(): string
    {
        if (!in_array($this->tipo_certificado, ['anexo_13', 'anexo_14'])) {
            return 'sin_vencimiento';
        }

        if (!$this->fecha_emision) {
            return 'desconocido';
        }

        $fechaVencimiento = $this->fecha_emision->addYears($this->vigencia ?? 2);
        
        return $fechaVencimiento->isFuture() ? 'vigente' : 'vencido';
    }

    /**
     * Obtener fecha de vencimiento calculada
     */
    public function getFechaVencimientoAttribute()
    {
        if (!in_array($this->tipo_certificado, ['anexo_13', 'anexo_14'])) {
            return null;
        }

        if (!$this->fecha_emision) {
            return null;
        }

        return $this->fecha_emision->addYears($this->vigencia ?? 2);
    }

    /**
     * Obtener clase CSS para el estado
     */
    public function getEstadoClaseAttribute(): string
    {
        return match($this->estado) {
            'vigente' => 'success',
            'vencido' => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Obtener icono para el tipo
     */
    public function getTipoIconoAttribute(): string
    {
        return match($this->tipo_certificado) {
            'anexo_13' => 'fa-building',
            'anexo_14' => 'fa-factory',
            'evento_publico' => 'fa-ticket-alt',
            default => 'fa-file',
        };
    }

    /**
     * Obtener nombre del tipo
     */
    public function getTipoNombreAttribute(): string
    {
        return match($this->tipo_certificado) {
            'anexo_13' => 'ITSE 13 (Riesgo Bajo/Medio)',
            'anexo_14' => 'ITSE 14 (Riesgo Alto)',
            'evento_publico' => 'ECSE (Evento Público)',
            default => 'Desconocido',
        };
    }
}
