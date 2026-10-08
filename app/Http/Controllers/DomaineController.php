<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDomaineRequest;
use App\Http\Requests\UpdateDomaineRequest;
use App\Models\Domaine;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DomaineController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Domaine::class, 'domaine', ['except' => ['index', 'show']]);
    }

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Domaine::class);
        $domaines = Domaine::query()
            ->when($request->search, fn ($q, $search) => $q->where('nom_domaine', 'like', "%{$search}%"))
            ->orderBy('nom_domaine')
            ->get();

        return view('domaines.index', compact('domaines'));
    }

    public function create(): View
    {
        return view('domaines.create');
    }

    public function store(StoreDomaineRequest $request): RedirectResponse
    {
        Domaine::create($request->validated());

        return redirect()->route('domaines.index')->with('success', 'Domaine créé avec succès.');
    }

    public function show(Domaine $domaine): View
    {
        $this->authorize('view', $domaine);

        $domaine->load('themes');

        return view('domaines.show', compact('domaine'));
    }

    public function edit(Domaine $domaine): View
    {
        return view('domaines.edit', compact('domaine'));
    }

    public function update(UpdateDomaineRequest $request, Domaine $domaine): RedirectResponse
    {
        $domaine->update($request->validated());

        return redirect()->route('domaines.index')->with('success', 'Domaine mis à jour avec succès.');
    }

    public function destroy(Domaine $domaine): RedirectResponse
    {
        $domaine->delete();

        return redirect()->route('domaines.index')->with('success', 'Domaine supprimé avec succès.');
    }
}
