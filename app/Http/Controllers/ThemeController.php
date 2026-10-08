<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreThemeRequest;
use App\Http\Requests\UpdateThemeRequest;
use App\Models\Domaine;
use App\Models\Theme;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ThemeController extends Controller
{
    public function __construct(protected NotificationService $notificationService)
    {
        $this->authorizeResource(Theme::class, 'theme', ['except' => ['index', 'show']]);
    }

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Theme::class);
        $domaines = Domaine::orderBy('nom_domaine')->get();

        $themes = Theme::with('domaine')
            ->withCount('plans')
            ->when($request->search, function ($q, $search) {
                $q->where(function ($query) use ($search) {
                    $query->where('intitule_theme', 'like', "%{$search}%")
                        ->orWhereHas('domaine', fn ($dq) => $dq->where('nom_domaine', 'like', "%{$search}%"));
                });
            })
            ->when($request->domaines_id, fn ($q, $id) => $q->where('domaines_id', $id))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->integer('status')))
            ->latest()
            ->get();

        return view('themes.index', compact('themes', 'domaines'));
    }

    public function create(): View
    {
        $domaines = Domaine::orderBy('nom_domaine')->get();

        return view('themes.create', compact('domaines'));
    }

    public function store(StoreThemeRequest $request): RedirectResponse
    {
        $theme = Theme::create($request->validated());

        if ((int) $theme->status === Theme::STATUS_ACTIF) {
            $theme->load('domaine');
            $this->notificationService->notifyNewTrainingOffer($theme);
        }

        return redirect()->route('themes.index')->with('success', 'Thème créé avec succès.');
    }

    public function show(Theme $theme): View
    {
        $this->authorize('view', $theme);

        $theme->load(['domaine', 'plans.etablissement', 'actions.entreprise']);

        return view('themes.show', compact('theme'));
    }

    public function edit(Theme $theme): View
    {
        $domaines = Domaine::orderBy('nom_domaine')->get();

        return view('themes.edit', compact('theme', 'domaines'));
    }

    public function update(UpdateThemeRequest $request, Theme $theme): RedirectResponse
    {
        $wasInactive = (int) $theme->status !== Theme::STATUS_ACTIF;
        $theme->update($request->validated());

        if ($wasInactive && (int) $theme->status === Theme::STATUS_ACTIF) {
            $theme->load('domaine');
            $this->notificationService->notifyNewTrainingOffer($theme);
        }

        return redirect()->route('themes.index')->with('success', 'Thème mis à jour avec succès.');
    }

    public function destroy(Theme $theme): RedirectResponse
    {
        $theme->delete();

        return redirect()->route('themes.index')->with('success', 'Thème supprimé avec succès.');
    }
}
