<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlanRequest;
use App\Http\Requests\UpdatePlanRequest;
use App\Models\Etablissement;
use App\Models\Plan;
use App\Models\Theme;
use App\Services\UserDataScope;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PlanController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Plan::class, 'plan', ['except' => ['index', 'show']]);
    }

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Plan::class);
        $plans = Plan::forUser($request->user())
            ->with(['etablissement.region', 'theme.domaine'])
            ->when($request->search, function ($q, $search) {
                $q->whereHas('theme', fn ($tq) => $tq->where('intitule_theme', 'like', "%{$search}%"));
            })
            ->latest()
            ->get();

        return view('plans.index', compact('plans'));
    }

    public function create(Request $request): View
    {
        $etablissements = UserDataScope::for($request->user())
            ->etablissementsForSelection(Etablissement::query())
            ->with('region')
            ->orderBy('nom_efp')
            ->get();
        $themes = Theme::where('status', Theme::STATUS_ACTIF)->with('domaine')->orderBy('intitule_theme')->get();

        return view('plans.create', compact('etablissements', 'themes'));
    }

    public function store(StorePlanRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->user()->isCompany()) {
            $data['entreprises_id'] = $request->user()->entreprise?->id;
            $data['status'] = Plan::STATUS_BROUILLON;
        }

        Plan::create($data);

        return redirect()->route('plans.index')->with('success', 'Plan de formation créé avec succès.');
    }

    public function show(Plan $plan): View
    {
        $this->authorize('view', $plan);

        $plan->load(['etablissement.region', 'theme.domaine', 'entreprise']);

        return view('plans.show', compact('plan'));
    }

    public function edit(Request $request, Plan $plan): View
    {
        $this->authorize('update', $plan);

        $etablissements = UserDataScope::for($request->user())
            ->etablissementsForSelection(Etablissement::query())
            ->with('region')
            ->orderBy('nom_efp')
            ->get();
        $themes = Theme::where('status', Theme::STATUS_ACTIF)->with('domaine')->orderBy('intitule_theme')->get();

        return view('plans.edit', compact('plan', 'etablissements', 'themes'));
    }

    public function update(UpdatePlanRequest $request, Plan $plan): RedirectResponse
    {
        $this->authorize('update', $plan);

        $except = [];
        if ($request->user()->isCompany() || $request->user()->isSuperAdmin()) {
            $except[] = 'status';
        }

        $plan->update($request->safe()->except($except));

        return redirect()->route('plans.index')->with('success', 'Plan de formation mis à jour avec succès.');
    }

    public function destroy(Plan $plan): RedirectResponse
    {
        $plan->delete();

        return redirect()->route('plans.index')->with('success', 'Plan de formation supprimé avec succès.');
    }

    public function cancel(Plan $plan): RedirectResponse
    {
        $this->authorize('cancel', $plan);

        $plan->update(['status' => Plan::STATUS_ANNULE]);

        return back()->with('success', 'Plan de formation annulé avec succès.');
    }

    public function approve(Plan $plan): RedirectResponse
    {
        $this->authorize('approve', $plan);

        $plan->update(['status' => Plan::STATUS_PLANIFIE]);

        return back()->with('success', 'Plan de formation validé avec succès.');
    }

    public function reject(Plan $plan): RedirectResponse
    {
        $this->authorize('reject', $plan);

        $plan->update(['status' => Plan::STATUS_ANNULE]);

        return back()->with('success', 'Plan de formation refusé.');
    }
}
