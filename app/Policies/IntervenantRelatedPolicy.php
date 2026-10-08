<?php

namespace App\Policies;

use App\Models\User;

abstract class IntervenantRelatedPolicy extends ScopedPolicy
{
    abstract protected function permission(): string;

    public function viewAny(User $user): bool
    {
        return $user->can($this->permission()) && ! $user->isCompany();
    }

    public function view(User $user, $model): bool
    {
        if (! $user->can($this->permission()) || $user->isCompany()) {
            return false;
        }

        if ($user->isTrainer()) {
            return $this->ownsTrainerRecord($user, $model);
        }

        return $this->hasUnrestrictedAccess($user) || $this->inScope($user, $model);
    }

    public function create(User $user): bool
    {
        if (! $user->can($this->permission()) || $user->isCompany()) {
            return false;
        }

        return ! $user->isTrainer() || $user->intervenant !== null;
    }

    public function update(User $user, $model): bool
    {
        return $this->view($user, $model);
    }

    public function delete(User $user, $model): bool
    {
        return $this->view($user, $model);
    }

    protected function ownsTrainerRecord(User $user, $model): bool
    {
        return $user->intervenant !== null
            && (int) $model->intervenant_id === (int) $user->intervenant->id;
    }
}
