<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreActionRequest;
use App\Http\Requests\UpdateActionRequest;
use App\Models\Action;
use App\Models\Etablissement;
use App\Models\Entreprise;
use App\Models\Intervenant;
use App\Models\Theme;
use App\Services\NotificationService;
use App\Services\UserDataScope;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActionController extends Controller
{
    public function __construct(protected NotificationService $notificationService)
    {
        $this->authorizeResource(Action::class, 'action', ['except' => ['index', 'show']]);
    }

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Action::class);
        $actions = Action::forUser($request->user())
            ->with(['theme.domaine', 'entreprise', 'etablissement.region'])
            ->when($request->search, function ($q, $search) {
                $q->whereHas('theme', fn ($tq) => $tq->where('intitule_theme', 'like', "%{$search}%"));
            })
            ->when($request->filled('status'), fn ($q) => $q->where('status', (int) $request->status))
            ->latest()
            ->get();

        return view('actions.index', compact('actions'));
    }

    public function create(Request $request): View
    {
        $etablissements = UserDataScope::for($request->user())
            ->etablissementsForSelection(Etablissement::query())
            ->with('region')
            ->orderBy('nom_efp')
            ->get();
        $themes = Theme::where('status', Theme::STATUS_ACTIF)->with('domaine')->orderBy('intitule_theme')->get();
        $entreprises = $request->user()->isCompany()
            ? collect()
            : Entreprise::forUser($request->user())->orderBy('raison')->get();

        return view('actions.create', compact('etablissements', 'themes', 'entreprises'));
    }

    public function store(StoreActionRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $action = Action::create($data);

        if ((int) $action->status === Action::STATUS_APPROVED) {
            $action->load('theme.domaine');
            $this->notificationService->notifyIntervenantsForAction($action);
        }

        return redirect()->route('actions.index')->with('success', 'Action de formation enregistrée avec succès.');
    }

    public function show(Action $action): View
    {
        $this->authorize('view', $action);

        $action->load(['theme.domaine', 'entreprise', 'etablissement.region', 'intervenants']);

        $user = auth()->user();
        $availableIntervenants = collect();
        if ($user->can('assignIntervenant', $action)) {
            $availableIntervenants = Intervenant::forUser($user)
                ->orderBy('nom')
                ->orderBy('prenom')
                ->get();
        }

        return view('actions.show', compact('action', 'availableIntervenants'));
    }

    public function edit(Request $request, Action $action): View
    {
        $this->authorize('update', $action);

        $etablissements = UserDataScope::for($request->user())
            ->etablissementsForSelection(Etablissement::query())
            ->with('region')
            ->orderBy('nom_efp')
            ->get();
        $themes = Theme::where('status', Theme::STATUS_ACTIF)->with('domaine')->orderBy('intitule_theme')->get();
        $entreprises = $request->user()->isCompany()
            ? collect()
            : Entreprise::forUser($request->user())->orderBy('raison')->get();

        return view('actions.edit', compact('action', 'etablissements', 'themes', 'entreprises'));
    }

    public function update(UpdateActionRequest $request, Action $action): RedirectResponse
    {
        $this->authorize('update', $action);

        $except = [];
        if ($request->user()->isSuperAdmin()) {
            $except[] = 'status';
        }

        $action->update($request->safe()->except($except));

        return redirect()->route('actions.index')->with('success', 'Action de formation mise à jour avec succès.');
    }

    public function destroy(Action $action): RedirectResponse
    {
        $this->authorize('delete', $action);

        $action->delete();

        return redirect()->route('actions.index')->with('success', 'Action de formation supprimée avec succès.');
    }

    public function start(Action $action): RedirectResponse
    {
        $this->authorize('start', $action);

        $action->update(['status' => Action::STATUS_IN_PROGRESS]);

        return back()->with('success', 'Action de formation démarrée.');
    }

    public function finish(Action $action): RedirectResponse
    {
        $this->authorize('finish', $action);

        $action->update(['status' => Action::STATUS_COMPLETED]);

        return back()->with('success', 'Action de formation terminée.');
    }

    public function cancel(Action $action): RedirectResponse
    {
        $this->authorize('cancel', $action);

        $action->update(['status' => Action::STATUS_CANCELLED]);

        return back()->with('success', 'Demande annulée avec succès.');
    }

    public function approve(Action $action): RedirectResponse
    {
        $this->authorize('approve', $action);

        $action->update(['status' => Action::STATUS_APPROVED]);

        $action->load('theme.domaine');
        $this->notificationService->notifyIntervenantsForAction($action);

        return back()->with('success', 'Demande approuvée avec succès.');
    }

    public function reject(Action $action): RedirectResponse
    {
        $this->authorize('reject', $action);

        $action->update(['status' => Action::STATUS_CANCELLED]);

        return back()->with('success', 'Demande refusée.');
    }

    public function assign(Request $request, Action $action): RedirectResponse
    {
        $this->authorize('assignIntervenant', $action);

        $validated = $request->validate([
            'intervenant_id' => ['required', 'exists:intervenants,id'],
        ]);

        $intervenant = Intervenant::findOrFail($validated['intervenant_id']);

        $action->intervenants()->syncWithoutDetaching([
            $intervenant->id => ['status' => Action::INTERVENANT_ASSIGNED],
        ]);

        return back()->with('success', 'Intervenant affecté avec succès.');
    }

    public function rejectIntervenant(Action $action, Intervenant $intervenant): RedirectResponse
    {
        $this->authorize('assignIntervenant', $action);

        if (! $action->intervenants()->where('intervenant_id', $intervenant->id)->exists()) {
            return back()->with('error', 'Cet intervenant n\'est pas lié à cette action.');
        }

        $action->intervenants()->updateExistingPivot($intervenant->id, [
            'status' => Action::INTERVENANT_REJECTED,
        ]);

        return back()->with('success', 'Candidature refusée.');
    }
}
