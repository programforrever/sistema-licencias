<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determina si el usuario puede gestionar firmas (solo admin)
     */
    public function manageSignatures(User $user): bool
    {
        return $user->hasRole('admin');
    }
}
