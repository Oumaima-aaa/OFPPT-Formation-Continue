<?php

namespace App\Http\Controllers;

use App\Models\Action;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        if ($user->isCompany()) {
            return $this->companyDashboard($user);
        }

        if ($user->isRegionalManager()) {
            return $this->regionalDashboard($user);
        }

        if ($user->isLocalManager()) {
            return $this->localDashboard($user);
        }

        if ($user->isTrainer()) {
            return $this->trainerDashboard($user);
        }

        return $this->centralDashboard();
    }

    protected function centralDashboard(): View
    {
        $stats = [
            'entreprises' => \App\Models\Entreprise::count(),
            'actions' => Action::count(),
            'plans' => Plan::count(),
            'themes' => \App\Models\Theme::where('status', \App\Models\Theme::STATUS_ACTIF)->count(),
        ];

        return view('dashboard.central', compact('stats'));
    }

    protected function companyDashboard(User $user): View
    {
        $entreprise = $user->entreprise;
        abort_unless($entreprise, 403, 'Aucune entreprise associée à ce compte.');

        $plansQuery = Plan::forUser($user);
        $actionsQuery = Action::forUser($user);

        $stats = [
            'total_plans' => (clone $plansQuery)->count(),
            'total_actions' => (clone $actionsQuery)->count(),
            'completed_actions' => (clone $actionsQuery)->where('status', Action::STATUS_COMPLETED)->count(),
            'pending_actions' => (clone $actionsQuery)->whereIn('status', [Action::STATUS_PENDING, Action::STATUS_APPROVED])->count(),
            'planned_participants' => (clone $plansQuery)->sum('nbparticipantmaxi'),
        ];

        $recentActions = Action::forUser($user)
            ->with(['theme', 'etablissement'])
            ->latest()
            ->limit(5)
            ->get();

        $notifications = $user->unreadNotifications()->latest()->limit(5)->get();

        return view('dashboard.entreprise', compact('stats', 'recentActions', 'notifications', 'entreprise'));
    }

    protected function regionalDashboard(User $user): View
    {
        $regionId = $user->region_id;

        $stats = [
            'etablissements' => \App\Models\Etablissement::forUser($user)->count(),
            'actions' => Action::forUser($user)->count(),
            'plans' => Plan::forUser($user)->count(),
            'entreprises' => \App\Models\Entreprise::forUser($user)->count(),
            'intervenants' => \App\Models\Intervenant::forUser($user)->count(),
        ];

        $actionsByStatus = Action::forUser($user)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->mapWithKeys(fn ($total, $status) => [Action::statusLabels()[(int) $status] ?? $status => $total]);

        $region = $user->assignedRegion;

        return view('dashboard.regional', compact('stats', 'actionsByStatus', 'user', 'region'));
    }

    protected function localDashboard(User $user): View
    {
        $stats = [
            'plans' => Plan::forUser($user)->count(),
            'actions' => Action::forUser($user)->count(),
            'entreprises' => \App\Models\Entreprise::forUser($user)->count(),
            'intervenants' => \App\Models\Intervenant::forUser($user)->count(),
        ];

        $recentPlans = Plan::forUser($user)
            ->with(['theme'])
            ->latest()
            ->limit(5)
            ->get();

        $etablissement = $user->assignedEstablishment;

        return view('dashboard.local', compact('stats', 'recentPlans', 'user', 'etablissement'));
    }

    protected function trainerDashboard(User $user): View
    {
        $intervenant = $user->intervenant;
        abort_unless($intervenant, 403, 'Aucun profil intervenant associé à ce compte.');

        $stats = [
            'competences' => $intervenant->competences()->count(),
            'diplomes' => $intervenant->diplomes()->count(),
            'certifications' => $intervenant->certifications()->count(),
            'actions' => Action::forUser($user)->count(),
        ];

        $recentActions = Action::forUser($user)
            ->with(['theme', 'etablissement'])
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard.trainer', compact('stats', 'recentActions', 'intervenant'));
    }
}
