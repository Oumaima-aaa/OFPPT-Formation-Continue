<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEtablissementRequest;
use App\Http\Requests\UpdateEtablissementRequest;
use App\Models\Etablissement;
use App\Models\Region;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EtablissementController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Etablissement::class, 'etablissement', ['except' => ['index', 'show']]);
    }

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Etablissement::class);
        $etablissements = Etablissement::forUser($request->user())
            ->with(['region', 'user'])
            ->when($request->search, function ($q, $search) {
                $q->where('nom_efp', 'like', "%{$search}%")
                    ->orWhere('ville', 'like', "%{$search}%");
            })
            ->latest()
            ->get();

        return view('etablissements.index', compact('etablissements'));
    }

    public function create(Request $request): View
    {
        $regions = Region::forUser($request->user())->orderBy('nom_region')->get();
        $users = User::role([User::ROLE_LOCAL_MANAGER, User::ROLE_REGIONAL_MANAGER])->orderBy('first_name')->get();

        return view('etablissements.create', compact('regions', 'users'));
    }

    public function store(StoreEtablissementRequest $request): RedirectResponse
    {
        Etablissement::create($request->validated());

        return redirect()->route('etablissements.index')->with('success', 'Établissement créé avec succès.');
    }

    public function show(Etablissement $etablissement): View
    {
        $this->authorize('view', $etablissement);

        $etablissement->load(['region', 'user', 'plans.theme', 'actions.theme']);

        return view('etablissements.show', compact('etablissement'));
    }

    public function edit(Request $request, Etablissement $etablissement): View
    {
        $regions = Region::forUser($request->user())->orderBy('nom_region')->get();
        $users = User::role([User::ROLE_LOCAL_MANAGER, User::ROLE_REGIONAL_MANAGER])->orderBy('first_name')->get();

        return view('etablissements.edit', compact('etablissement', 'regions', 'users'));
    }

    public function update(UpdateEtablissementRequest $request, Etablissement $etablissement): RedirectResponse
    {
        $etablissement->update($request->validated());

        return redirect()->route('etablissements.index')->with('success', 'Établissement mis à jour avec succès.');
    }

    public function destroy(Etablissement $etablissement): RedirectResponse
    {
        $etablissement->delete();

        return redirect()->route('etablissements.index')->with('success', 'Établissement supprimé avec succès.');
    }
}
