<?php

namespace App\Policies;

use App\Models\Plan;
use App\Models\User;

class PlanPolicy extends ScopedPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('plans.view') || $user->can('plans.manage');
    }

    public function view(User $user, Plan $plan): bool
    {
        if (! $this->viewAny($user)) {
            return false;
        }

        return $this->hasUnrestrictedAccess($user) || $this->inScope($user, $plan);
    }

    public function create(User $user): bool
    {
        return $user->can('plans.manage')
            && ($user->isCompany() || $this->canManagePlans($user));
    }

    public function update(User $user, Plan $plan): bool
    {
        if (! $this->view($user, $plan)) {
            return false;
        }

        if ($user->isSuperAdmin()) {
            return false;
        }

        if ($user->isCompany()) {
            return $plan->canBeEditedByCompany();
        }

        return $this->canManagePlans($user);
    }

    public function delete(User $user, Plan $plan): bool
    {
        if ($user->isCompany() || $user->isSuperAdmin()) {
            return false;
        }

        return $this->canManagePlans($user) && $this->view($user, $plan);
    }

    public function cancel(User $user, Plan $plan): bool
    {
        if (! $this->view($user, $plan)) {
            return false;
        }

        if ($user->isSuperAdmin()) {
            return false;
        }

        if ($user->isCompany()) {
            return $plan->canBeCancelledByCompany();
        }

        return $this->canManagePlans($user) && $plan->canBeCancelled();
    }

    public function approve(User $user, Plan $plan): bool
    {
        return $this->canValidatePlans($user)
            && $this->view($user, $plan)
            && $plan->canBeApproved();
    }

    public function reject(User $user, Plan $plan): bool
    {
        return $this->approve($user, $plan);
    }

    protected function canValidatePlans(User $user): bool
    {
        return $user->isRegionalManager() || $user->isLocalManager();
    }

    protected function canManagePlans(User $user): bool
    {
        return $user->isRegionalManager() || $user->isLocalManager();
    }
}
