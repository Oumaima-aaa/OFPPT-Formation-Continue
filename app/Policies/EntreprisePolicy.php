<?php

namespace App\Policies;

use App\Models\Entreprise;
use App\Models\User;

class EntreprisePolicy extends ScopedPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('entreprises.manage');
    }

    public function view(User $user, Entreprise $entreprise): bool
    {
        return $user->can('entreprises.manage')
            && ($this->hasUnrestrictedAccess($user) || $this->inScope($user, $entreprise));
    }

    public function create(User $user): bool
    {
        return $user->can('entreprises.manage');
    }

    public function update(User $user, Entreprise $entreprise): bool
    {
        return $this->view($user, $entreprise);
    }

    public function delete(User $user, Entreprise $entreprise): bool
    {
        return $user->can('entreprises.manage') && $this->hasUnrestrictedAccess($user);
    }
}
