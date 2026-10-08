<?php

namespace App\Policies;

use App\Models\User;
use App\Services\UserDataScope;

class UserPolicy extends ScopedPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('users.view');
    }

    public function view(User $user, User $model): bool
    {
        if (! $user->can('users.view')) {
            return false;
        }

        return $this->hasUnrestrictedAccess($user) || UserDataScope::for($user)->canAccess($model);
    }

    public function create(User $user): bool
    {
        return $user->can('users.create') && $this->hasUnrestrictedAccess($user);
    }

    public function update(User $user, User $model): bool
    {
        if (! $user->can('users.edit')) {
            return false;
        }

        return $this->hasUnrestrictedAccess($user)
            || ($this->view($user, $model) && ! $model->isSuperAdmin());
    }

    public function delete(User $user, User $model): bool
    {
        if ($user->id === $model->id) {
            return false;
        }

        return $user->can('users.delete') && $this->hasUnrestrictedAccess($user);
    }

    public function activate(User $user, User $model): bool
    {
        return $user->can('users.activate')
            && ($this->hasUnrestrictedAccess($user) || $this->view($user, $model));
    }

    public function deactivate(User $user, User $model): bool
    {
        if ($user->id === $model->id) {
            return false;
        }

        return $user->can('users.deactivate')
            && ($this->hasUnrestrictedAccess($user) || $this->view($user, $model));
    }

    public function resetPassword(User $user, User $model): bool
    {
        return $user->can('users.edit')
            && ($this->hasUnrestrictedAccess($user) || $this->view($user, $model));
    }
}
