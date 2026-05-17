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
        'dias_evento',
        'organizador_nombre',
        'organizador_dni',
        'estado',
        'estado_pago',
        'monto_pago',
        'observaciones',
        'doc_solicitud',
        'doc_plano',
        'doc_dni_copia',
        'doc_comprobante_pago',
        'doc_comprobante_yape',
        'doc_otros',
        'licencia_id',
        'token_revision',
    ];

    protected $casts = [
        'fecha_evento' => 'date',
    ];

    public function licencia()
    {
        return $this->belongsTo(Licencia::class);
    }

    public function revisores()
    {
        return $this->hasMany(RevisorSolicitud::class);
    }

    public function revisiones()
    {
        return $this->hasMany(RevisionSolicitud::class);
    }

    /**
     * Obtiene el monto pagado por la solicitud
     * Si no está registrado, calcula según el tipo de certificado
     */
    public function getMontoPagoCalculado()
    {
        // Si ya tiene monto registrado y es válido, retornarlo
        if ($this->monto_pago && $this->monto_pago > 0) {
            return (float) $this->monto_pago;
        }

        // Tabla de precios (debe coincidir con la del formulario)
        $precios = [
            'evento_publico' => 178.90,
            'anexo_13' => [
                'bajo'  => 99.80,
                'medio' => 133.80
            ],
            'anexo_14' => [
                'alto'    => 546.40,
                'muyalto' => 546.40
            ]
        ];

        // Si es evento público, retornar precio fijo
        if ($this->tipo_certificado === 'evento_publico') {
            return $precios['evento_publico'];
        }

        // Para anexo_13 y 14, intentar determinar el nivel de riesgo
        // Si hay área registrada, usar como referencia
        $area = (float) ($this->area_edificacion ?? 0);
        
        // Determinar nivel de riesgo basado en la actividad
        $actividad = strtolower($this->actividad ?? '');
        
        // Riesgos bajo para anexo_13
        if ($this->tipo_certificado === 'anexo_13') {
            // Bodega, tienda, peluquería son riesgo bajo
            if (strpos($actividad, 'bodega') !== false || 
                strpos($actividad, 'tienda') !== false || 
                strpos($actividad, 'peluquer') !== false) {
                return 99.80;
            }
            // Farmacia, restaurante, oficina son riesgo medio
            return 133.80; // Promedio para riesgo medio
        }

        // Para anexo_14
        if ($this->tipo_certificado === 'anexo_14') {
            // Por defecto, usar precio más alto
            return 546.40;
        }

        // Valor por defecto si no se puede determinar
        return 0.00;
    }
}