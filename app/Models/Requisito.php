<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Requisito extends Model
{
    protected $fillable = [
        'licencia_id',
        'nombre_documento',
        'estado',
        'archivo',
    ];

    public function licencia()
    {
        return $this->belongsTo(Licencia::class);
    }
}