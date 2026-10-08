<?php

namespace App\Policies;

use App\Models\Intervenant;
use App\Models\User;

class IntervenantPolicy extends ScopedPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('intervenants.manage') && ! $user->isCompany() && ! $user->isTrainer();
    }

    public function view(User $user, Intervenant $intervenant): bool
    {
        if (! $user->can('intervenants.manage') || $user->isCompany()) {
            return false;
        }

        if ($user->isTrainer()) {
            return $this->isOwnIntervenant($user, $intervenant);
        }

        return $this->hasUnrestrictedAccess($user) || $this->inScope($user, $intervenant);
    }

    public function create(User $user): bool
    {
        return $user->can('intervenants.manage') && ! $user->isCompany() && ! $user->isTrainer();
    }

    public function update(User $user, Intervenant $intervenant): bool
    {
        return $this->view($user, $intervenant);
    }

    public function delete(User $user, Intervenant $intervenant): bool
    {
        if ($user->isTrainer() || $user->isCompany()) {
            return false;
        }

        return $user->can('intervenants.manage')
            && ($this->hasUnrestrictedAccess($user) || $this->inScope($user, $intervenant));
    }

    protected function isOwnIntervenant(User $user, Intervenant $intervenant): bool
    {
        return $user->intervenant !== null
            && (int) $user->intervenant->id === (int) $intervenant->id;
    }
}
