<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RevisionSolicitud extends Model
{
    protected $table = 'revisiones_solicitud';

    protected $fillable = [
        'solicitud_id',
        'revisor_solicitud_id',
        'notas',
        'documento_revision',
        'resultado_revision',
        'entregado_at',
    ];

    protected $casts = [
        'entregado_at' => 'datetime',
    ];

    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class);
    }

    public function revisor()
    {
        return $this->belongsTo(RevisorSolicitud::class, 'revisor_solicitud_id');
    }
}
