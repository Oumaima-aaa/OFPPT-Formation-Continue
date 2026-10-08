<?php

namespace App\Policies;

use App\Models\Region;
use App\Models\User;

class RegionPolicy extends ScopedPolicy
{
    public function viewAny(User $user): bool
    {
        return ($user->can('regions.view') || $user->can('regions.manage'))
            && ($this->hasUnrestrictedAccess($user) || $user->isRegionalManager());
    }

    public function view(User $user, Region $region): bool
    {
        return ($user->can('regions.view') || $user->can('regions.manage'))
            && $this->inScope($user, $region);
    }

    public function create(User $user): bool
    {
        return $user->can('regions.manage') && $this->hasUnrestrictedAccess($user);
    }

    public function update(User $user, Region $region): bool
    {
        return $user->can('regions.manage') && $this->hasUnrestrictedAccess($user);
    }

    public function delete(User $user, Region $region): bool
    {
        return $user->can('regions.manage') && $this->hasUnrestrictedAccess($user);
    }
}
