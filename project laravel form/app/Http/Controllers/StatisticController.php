<?php

namespace App\Http\Controllers;

use App\Models\Action;
use App\Models\Entreprise;
use App\Models\Plan;
use App\Models\Theme;
use App\Services\UserDataScope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StatisticController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        if ($user->isCompany()) {
            return $this->companyStatistics($user);
        }

        $scope = UserDataScope::for($user);

        $plansByYear = $scope->plans(Plan::query())
            ->select('exercice as year', DB::raw('count(*) as total'))
            ->groupBy('exercice')
            ->orderBy('exercice')
            ->pluck('total', 'year');

        $actionsQuery = $scope->actions(Action::query())
            ->join('etablissements', 'actions.etablissements_id', '=', 'etablissements.id')
            ->join('regions', 'etablissements.regions_id', '=', 'regions.id');

        $actionsByRegion = (clone $actionsQuery)
            ->select('regions.nom_region as region', DB::raw('count(*) as total'))
            ->groupBy('regions.nom_region')
            ->pluck('total', 'region');

        $themesRequested = $scope->plans(Plan::query())
            ->join('themes', 'plans.themes_id', '=', 'themes.id')
            ->select('themes.intitule_theme', DB::raw('count(*) as plans_count'))
            ->groupBy('themes.intitule_theme')
            ->orderByDesc('plans_count')
            ->limit(10)
            ->pluck('plans_count', 'intitule_theme');

        $entreprisesByRegion = $scope->entreprises(Entreprise::query())
            ->join('actions', 'entreprises.id', '=', 'actions.entreprises_id')
            ->join('etablissements', 'actions.etablissements_id', '=', 'etablissements.id')
            ->join('regions', 'etablissements.regions_id', '=', 'regions.id')
            ->select('regions.nom_region as region', DB::raw('count(distinct entreprises.id) as total'))
            ->groupBy('regions.nom_region')
            ->pluck('total', 'region');

        $scopeLabel = match (true) {
            $user->isSuperAdmin() => 'Vue globale',
            $user->isRegionalManager() => 'Région : '.($user->assignedRegion?->nom_region ?? '—'),
            $user->isLocalManager() => 'Établissement : '.($user->assignedEstablishment?->nom_efp ?? '—'),
            default => null,
        };

        return view('statistics.index', compact(
            'plansByYear',
            'actionsByRegion',
            'themesRequested',
            'entreprisesByRegion',
            'scopeLabel',
        ));
    }

    protected function companyStatistics($user): View
    {
        abort_unless($user->entreprise, 403, 'Aucune entreprise associée à ce compte.');

        $scope = UserDataScope::for($user);

        $plansByYear = $scope->plans(Plan::query())
            ->select('exercice as year', DB::raw('count(*) as total'))
            ->groupBy('exercice')
            ->orderBy('exercice')
            ->pluck('total', 'year');

        $actionsByStatus = $scope->actions(Action::query())
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->mapWithKeys(fn ($total, $status) => [Action::statusLabels()[(int) $status] ?? $status => $total]);

        $themesRequested = $scope->plans(Plan::query())
            ->join('themes', 'plans.themes_id', '=', 'themes.id')
            ->select('themes.intitule_theme', DB::raw('count(*) as plans_count'))
            ->groupBy('themes.intitule_theme')
            ->orderByDesc('plans_count')
            ->limit(10)
            ->pluck('plans_count', 'intitule_theme');

        $summary = [
            'total_plans' => $scope->plans(Plan::query())->count(),
            'total_actions' => $scope->actions(Action::query())->count(),
            'completed_actions' => $scope->actions(Action::query())->where('status', Action::STATUS_COMPLETED)->count(),
            'planned_participants' => $scope->plans(Plan::query())->sum('nbparticipantmaxi'),
        ];

        $scopeLabel = 'Entreprise : '.($user->entreprise->raison ?? '—');

        return view('statistics.company', compact(
            'plansByYear',
            'actionsByStatus',
            'themesRequested',
            'summary',
            'scopeLabel',
        ));
    }
}
