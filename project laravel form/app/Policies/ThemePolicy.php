<?php

namespace App\Policies;

use App\Models\Theme;
use App\Models\User;

class ThemePolicy extends ScopedPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('themes.view') || $user->can('themes.manage');
    }

    public function view(User $user, Theme $theme): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('themes.manage') && $this->hasUnrestrictedAccess($user);
    }

    public function update(User $user, Theme $theme): bool
    {
        return $this->create($user);
    }

    public function delete(User $user, Theme $theme): bool
    {
        return $this->create($user);
    }
}
