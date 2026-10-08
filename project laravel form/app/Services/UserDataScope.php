<?php

namespace App\Services;

use App\Models\Action;
use App\Models\Competence;
use App\Models\Entreprise;
use App\Models\Etablissement;
use App\Models\Intervenant;
use App\Models\Plan;
use App\Models\Region;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class UserDataScope
{
    public function __construct(protected User $user) {}

    public static function for(?User $user): self
    {
        return new self($user ?? auth()->user());
    }

    public function isUnrestricted(): bool
    {
        return $this->user->isSuperAdmin();
    }

    public function regionId(): ?int
    {
        return $this->user->region_id;
    }

    public function establishmentId(): ?int
    {
        return $this->user->establishment_id;
    }

    public function regions(Builder $query): Builder
    {
        if ($this->isUnrestricted()) {
            return $query;
        }

        if ($this->user->isRegionalManager() && $this->regionId()) {
            return $query->where('id', $this->regionId());
        }

        return $query->whereRaw('0 = 1');
    }

    public function etablissements(Builder $query): Builder
    {
        if ($this->isUnrestricted()) {
            return $query;
        }

        if ($this->user->isRegionalManager() && $this->regionId()) {
            return $query->where('regions_id', $this->regionId());
        }

        if ($this->user->isLocalManager() && $this->establishmentId()) {
            return $query->where('id', $this->establishmentId());
        }

        return $query->whereRaw('0 = 1');
    }

    public function plans(Builder $query): Builder
    {
        if ($this->isUnrestricted()) {
            return $query;
        }

        if ($this->user->isRegionalManager() && $this->regionId()) {
            return $query->whereHas('etablissement', fn (Builder $q) => $q->where('regions_id', $this->regionId()));
        }

        if ($this->user->isLocalManager() && $this->establishmentId()) {
            return $query->where('etablissements_id', $this->establishmentId());
        }

        if ($this->user->isCompany()) {
            $entrepriseId = $this->user->entreprise?->id;

            return $query->where('entreprises_id', $entrepriseId);
        }

        if ($this->user->isTrainer() && $this->user->intervenant) {
            return $query->where('etablissements_id', $this->user->intervenant->etablissements_id);
        }

        return $query->whereRaw('0 = 1');
    }

    public function actions(Builder $query): Builder
    {
        if ($this->isUnrestricted()) {
            return $query;
        }

        if ($this->user->isCompany()) {
            return $query->where('entreprises_id', $this->user->entreprise?->id);
        }

        if ($this->user->isRegionalManager() && $this->regionId()) {
            return $query->whereHas('etablissement', fn (Builder $q) => $q->where('regions_id', $this->regionId()));
        }

        if ($this->user->isLocalManager() && $this->establishmentId()) {
            return $query->where('etablissements_id', $this->establishmentId());
        }

        if ($this->user->isTrainer() && $this->user->intervenant) {
            $intervenantId = $this->user->intervenant->id;

            return $query->whereHas('intervenants', fn (Builder $q) => $q->where('intervenants.id', $intervenantId));
        }

        return $query->whereRaw('0 = 1');
    }

    public function entreprises(Builder $query): Builder
    {
        if ($this->isUnrestricted()) {
            return $query;
        }

        if ($this->user->isCompany()) {
            return $query->where('users_id', $this->user->id);
        }

        if ($this->user->isRegionalManager() && $this->regionId()) {
            return $query->whereHas('actions.etablissement', fn (Builder $q) => $q->where('regions_id', $this->regionId()));
        }

        if ($this->user->isLocalManager() && $this->establishmentId()) {
            return $query->whereHas('actions', fn (Builder $q) => $q->where('etablissements_id', $this->establishmentId()));
        }

        return $query->whereRaw('0 = 1');
    }

    public function intervenants(Builder $query): Builder
    {
        if ($this->isUnrestricted()) {
            return $query;
        }

        if ($this->user->isRegionalManager() && $this->regionId()) {
            return $query->whereHas('etablissement', fn (Builder $q) => $q->where('regions_id', $this->regionId()));
        }

        if ($this->user->isLocalManager() && $this->establishmentId()) {
            return $query->where('etablissements_id', $this->establishmentId());
        }

        if ($this->user->isTrainer()) {
            return $query->where('user_id', $this->user->id);
        }

        return $query->whereRaw('0 = 1');
    }

    public function intervenantRelated(Builder $query): Builder
    {
        return $query->whereHas('intervenant', fn (Builder $q) => $this->intervenants($q));
    }

    public function users(Builder $query): Builder
    {
        if ($this->isUnrestricted()) {
            return $query;
        }

        if ($this->user->isRegionalManager() && $this->regionId()) {
            return $query->where(function (Builder $q) {
                $q->where('region_id', $this->regionId())
                    ->orWhereHas('establishment', fn (Builder $eq) => $eq->where('regions_id', $this->regionId()));
            });
        }

        if ($this->user->isLocalManager() && $this->establishmentId()) {
            return $query->where('establishment_id', $this->establishmentId());
        }

        return $query->whereRaw('0 = 1');
    }

    /** Active establishments available for company plan/action forms. */
    public function etablissementsForSelection(Builder $query): Builder
    {
        if ($this->isUnrestricted() || $this->user->isCompany()) {
            return $query->where('status', Etablissement::STATUS_ACTIF);
        }

        return $this->etablissements($query);
    }

    public function canAccess(Model $model): bool
    {
        if ($this->isUnrestricted()) {
            return true;
        }

        if ($model instanceof User) {
            return $this->users(User::query()->where('id', $model->id))->exists();
        }

        return match ($model::class) {
            Region::class => $this->user->isRegionalManager()
                && (int) $model->id === (int) $this->regionId(),
            Etablissement::class => $this->matchesEtablissement($model),
            Plan::class => $this->matchesPlan($model),
            Action::class => $this->matchesAction($model),
            Entreprise::class => $this->matchesEntreprise($model),
            Intervenant::class => $this->matchesIntervenant($model),
            Competence::class, \App\Models\Diplome::class, \App\Models\Certification::class => $this->matchesIntervenant($model->intervenant),
            default => false,
        };
    }

    protected function matchesEtablissement(Etablissement $etablissement): bool
    {
        if ($this->user->isRegionalManager() && $this->regionId()) {
            return (int) $etablissement->regions_id === (int) $this->regionId();
        }

        if ($this->user->isLocalManager() && $this->establishmentId()) {
            return (int) $etablissement->id === (int) $this->establishmentId();
        }

        return false;
    }

    protected function matchesPlan(Plan $plan): bool
    {
        if ($this->user->isCompany()) {
            return (int) $plan->entreprises_id === (int) $this->user->entreprise?->id;
        }

        if ($this->user->isTrainer() && $this->user->intervenant) {
            return (int) $plan->etablissements_id === (int) $this->user->intervenant->etablissements_id;
        }

        $plan->loadMissing('etablissement');

        return $plan->etablissement && $this->matchesEtablissement($plan->etablissement);
    }

    protected function matchesAction(Action $action): bool
    {
        if ($this->user->isCompany()) {
            return (int) $action->entreprises_id === (int) $this->user->entreprise?->id;
        }

        if ($this->user->isTrainer() && $this->user->intervenant) {
            return $action->intervenants()->where('intervenants.id', $this->user->intervenant->id)->exists();
        }

        $action->loadMissing('etablissement');

        return $action->etablissement && $this->matchesEtablissement($action->etablissement);
    }

    protected function matchesEntreprise(Entreprise $entreprise): bool
    {
        if ($this->user->isCompany()) {
            return (int) $entreprise->users_id === (int) $this->user->id;
        }

        if ($this->user->isRegionalManager() && $this->regionId()) {
            return $entreprise->actions()
                ->whereHas('etablissement', fn (Builder $q) => $q->where('regions_id', $this->regionId()))
                ->exists();
        }

        if ($this->user->isLocalManager() && $this->establishmentId()) {
            return $entreprise->actions()
                ->where('etablissements_id', $this->establishmentId())
                ->exists();
        }

        return false;
    }

    protected function matchesIntervenant(?Intervenant $intervenant): bool
    {
        if (! $intervenant) {
            return false;
        }

        if ($this->user->isTrainer()) {
            return (int) $intervenant->user_id === (int) $this->user->id;
        }

        $intervenant->loadMissing('etablissement');

        if (! $intervenant->etablissement) {
            return false;
        }

        return $this->matchesEtablissement($intervenant->etablissement);
    }
}
