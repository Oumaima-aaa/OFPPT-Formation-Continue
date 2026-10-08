<?php

namespace App\Providers;

use App\Models\Action;
use App\Models\Certification;
use App\Models\Competence;
use App\Models\Diplome;
use App\Models\Domaine;
use App\Models\Entreprise;
use App\Models\Etablissement;
use App\Models\Intervenant;
use App\Models\Plan;
use App\Models\Region;
use App\Models\Theme;
use App\Models\User;
use App\Policies\ActionPolicy;
use App\Policies\CertificationPolicy;
use App\Policies\CompetencePolicy;
use App\Policies\DiplomePolicy;
use App\Policies\DomainePolicy;
use App\Policies\EntreprisePolicy;
use App\Policies\EtablissementPolicy;
use App\Policies\IntervenantPolicy;
use App\Policies\PlanPolicy;
use App\Policies\RegionPolicy;
use App\Policies\ThemePolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Schema::defaultStringLength(125);

        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Region::class, RegionPolicy::class);
        Gate::policy(Domaine::class, DomainePolicy::class);
        Gate::policy(Theme::class, ThemePolicy::class);
        Gate::policy(Etablissement::class, EtablissementPolicy::class);
        Gate::policy(Entreprise::class, EntreprisePolicy::class);
        Gate::policy(Plan::class, PlanPolicy::class);
        Gate::policy(Action::class, ActionPolicy::class);
        Gate::policy(Intervenant::class, IntervenantPolicy::class);
        Gate::policy(Competence::class, CompetencePolicy::class);
        Gate::policy(Diplome::class, DiplomePolicy::class);
        Gate::policy(Certification::class, CertificationPolicy::class);

        $this->registerScopedRouteBindings();
    }

    protected function registerScopedRouteBindings(): void
    {
        $scoped = fn (string $model) => fn ($value) => $model::forUser(auth()->user())->findOrFail($value);

        Route::bind('region', $scoped(Region::class));
        Route::bind('etablissement', $scoped(Etablissement::class));
        Route::bind('entreprise', $scoped(Entreprise::class));
        Route::bind('plan', $scoped(Plan::class));
        Route::bind('action', $scoped(Action::class));
        Route::bind('intervenant', $scoped(Intervenant::class));
        Route::bind('competence', $scoped(Competence::class));
        Route::bind('diplome', $scoped(Diplome::class));
        Route::bind('certification', $scoped(Certification::class));
        Route::bind('user', $scoped(User::class));
    }
}
