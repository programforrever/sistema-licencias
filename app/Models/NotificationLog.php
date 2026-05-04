<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationLog extends Model
{
    protected $fillable = [
        'solicitud_id',
        'canal',
        'destinatario',
        'mensaje',
        'estado',
        'error_message',
        'cambio_estado',
    ];

    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class);
    }
}
