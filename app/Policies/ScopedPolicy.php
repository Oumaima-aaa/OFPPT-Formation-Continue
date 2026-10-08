<?php

namespace App\Policies;

use App\Models\User;
use App\Services\UserDataScope;
use Illuminate\Database\Eloquent\Model;

abstract class ScopedPolicy
{
    protected function inScope(User $user, Model $model): bool
    {
        return UserDataScope::for($user)->canAccess($model);
    }

    protected function hasUnrestrictedAccess(User $user): bool
    {
        return $user->isSuperAdmin();
    }
}
