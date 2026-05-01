<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActividadEconomica extends Model
{
    protected $table = 'actividades_economicas';
    
    protected $fillable = [
        'codigo',
        'descripcion',
        'categoria',
        'tasa_derecho',
    ];

    public function licencias()
    {
        return $this->hasMany(Licencia::class);
    }
}