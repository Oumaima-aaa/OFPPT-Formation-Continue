<?php

namespace App\Policies;

use App\Models\Domaine;
use App\Models\User;

class DomainePolicy extends ScopedPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('domaines.view') || $user->can('domaines.manage');
    }

    public function view(User $user, Domaine $domaine): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('domaines.manage') && $this->hasUnrestrictedAccess($user);
    }

    public function update(User $user, Domaine $domaine): bool
    {
        return $this->create($user);
    }

    public function delete(User $user, Domaine $domaine): bool
    {
        return $this->create($user);
    }
}
