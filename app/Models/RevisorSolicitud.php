<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RevisorSolicitud extends Model
{
    protected $table = 'revisores_solicitud';

    protected $fillable = [
        'solicitud_id',
        'email',
        'nombre_revisor',
        'estado_revision',
        'token_revisor',
        'enviado_at',
        'revisado_at',
    ];

    protected $casts = [
        'enviado_at' => 'datetime',
        'revisado_at' => 'datetime',
    ];

    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class);
    }

    public function revisiones()
    {
        return $this->hasMany(RevisionSolicitud::class);
    }
}
