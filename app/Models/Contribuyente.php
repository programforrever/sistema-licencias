<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contribuyente extends Model
{
    protected $fillable = [
        'dni_ruc',
        'tipo_persona',
        'nombres_razon_social',
        'direccion',
        'telefono',
        'email',
    ];

    public function licencias()
    {
        return $this->hasMany(Licencia::class);
    }
}