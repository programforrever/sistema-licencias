<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relación con la firma digital del usuario
     */
    public function signature()
    {
        return $this->hasOne(UserSignature::class);
    }

    /**
     * Relación con los certificados que este usuario ha firmado
     */
    public function licenciasFirmadas()
    {
        return $this->hasMany(Licencia::class, 'signed_by_user_id');
    }
}