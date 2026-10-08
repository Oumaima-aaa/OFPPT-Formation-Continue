<?php

namespace App\Policies;

use App\Models\Action;
use App\Models\User;

class ActionPolicy extends ScopedPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('actions.view') || $user->can('actions.manage');
    }

    public function view(User $user, Action $action): bool
    {
        if (! $this->viewAny($user)) {
            return false;
        }

        if ($user->isTrainer()) {
            return $this->inScope($user, $action);
        }

        return $this->hasUnrestrictedAccess($user) || $this->inScope($user, $action);
    }

    public function create(User $user): bool
    {
        return $user->can('actions.manage') && $this->canManageActions($user);
    }

    public function update(User $user, Action $action): bool
    {
        if (! $this->view($user, $action)) {
            return false;
        }

        return $this->canManageActions($user);
    }

    public function delete(User $user, Action $action): bool
    {
        if ($user->isSuperAdmin()) {
            return false;
        }

        return $this->canManageActions($user) && $this->view($user, $action);
    }

    public function start(User $user, Action $action): bool
    {
        return $this->canManageActions($user) && $this->view($user, $action) && $action->canBeStarted();
    }

    public function finish(User $user, Action $action): bool
    {
        return $this->canManageActions($user) && $this->view($user, $action) && $action->canBeFinished();
    }

    public function cancel(User $user, Action $action): bool
    {
        return $this->canManageActions($user) && $this->view($user, $action) && $action->canBeApproved();
    }

    public function approve(User $user, Action $action): bool
    {
        return $this->canManageActions($user)
            && $this->view($user, $action)
            && $action->canBeApproved();
    }

    public function reject(User $user, Action $action): bool
    {
        return $this->approve($user, $action);
    }

    public function assignIntervenant(User $user, Action $action): bool
    {
        return $this->canManageActions($user) && $this->view($user, $action)
            && in_array((int) $action->status, [Action::STATUS_PENDING, Action::STATUS_APPROVED], true);
    }

    protected function canManageActions(User $user): bool
    {
        return $user->isRegionalManager() || $user->isLocalManager();
    }
}
