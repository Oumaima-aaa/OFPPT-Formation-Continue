<?php

namespace App\Policies;

use App\Models\Etablissement;
use App\Models\User;

class EtablissementPolicy extends ScopedPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('etablissements.view') || $user->can('etablissements.manage');
    }

    public function view(User $user, Etablissement $etablissement): bool
    {
        return ($user->can('etablissements.view') || $user->can('etablissements.manage'))
            && ($this->hasUnrestrictedAccess($user) || $this->inScope($user, $etablissement));
    }

    public function create(User $user): bool
    {
        return $user->can('etablissements.manage')
            && ($this->hasUnrestrictedAccess($user) || $user->isRegionalManager());
    }

    public function update(User $user, Etablissement $etablissement): bool
    {
        if ($user->isLocalManager()) {
            return false;
        }

        return $user->can('etablissements.manage')
            && ($this->hasUnrestrictedAccess($user) || ($user->isRegionalManager() && $this->inScope($user, $etablissement)));
    }

    public function delete(User $user, Etablissement $etablissement): bool
    {
        return $this->hasUnrestrictedAccess($user) && $user->can('etablissements.manage');
    }
}
