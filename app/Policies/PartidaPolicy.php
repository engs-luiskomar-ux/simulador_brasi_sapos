<?php

namespace App\Policies;

use App\Models\Partida;
use App\Models\User;

class PartidaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Partida $partida): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isOrganizador();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Partida $partida): bool
    {
        return $user->isAdmin() || $user->isOrganizador();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Partida $partida): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Partida $partida): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Partida $partida): bool
    {
        return false;
    }

    public function simular(User $user, ?Partida $partida = null): bool
    {
        return $user->isAdmin() || $user->isOrganizador();
    }
}
