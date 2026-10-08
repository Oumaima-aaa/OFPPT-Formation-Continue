<?php

namespace App\Models\Concerns;

use App\Models\User;
use App\Services\UserDataScope;
use Illuminate\Database\Eloquent\Builder;

trait ScopedForUser
{
    public function scopeForUser(Builder $query, ?User $user = null): Builder
    {
        $scope = UserDataScope::for($user);

        return match (static::class) {
            \App\Models\Region::class => $scope->regions($query),
            \App\Models\Etablissement::class => $scope->etablissements($query),
            \App\Models\Plan::class => $scope->plans($query),
            \App\Models\Action::class => $scope->actions($query),
            \App\Models\Entreprise::class => $scope->entreprises($query),
            \App\Models\Intervenant::class => $scope->intervenants($query),
            \App\Models\Competence::class,
            \App\Models\Diplome::class,
            \App\Models\Certification::class => $scope->intervenantRelated($query),
            User::class => $scope->users($query),
            default => $query,
        };
    }
}
