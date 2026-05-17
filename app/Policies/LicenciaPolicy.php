<?php

namespace App\Policies;

use App\Models\Licencia;
use App\Models\User;

class LicenciaPolicy
{
    /**
     * Determina si el usuario puede firmar un certificado
     */
    public function sign(User $user, Licencia $licencia): bool
    {
        // El usuario debe ser admin o estar autenticado
        return $user->hasRole('admin') || auth()->check();
    }

    /**
     * Determina si el usuario puede ver un certificado
     */
    public function view(User $user, Licencia $licencia): bool
    {
        return $user->hasRole('admin') || auth()->check();
    }

    /**
     * Determina si el usuario puede actualizar un certificado
     */
    public function update(User $user, Licencia $licencia): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determina si el usuario puede crear un certificado
     */
    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }
}
