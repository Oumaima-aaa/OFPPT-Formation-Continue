<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDiplomeRequest;
use App\Http\Requests\UpdateDiplomeRequest;
use App\Models\Diplome;
use App\Models\Intervenant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DiplomeController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Diplome::class, 'diplome');
    }

    public function index(Request $request): View
    {
        $diplomes = Diplome::forUser($request->user())
            ->with('intervenant')
            ->when($request->search, fn ($q, $search) => $q->where('intitule', 'like', "%{$search}%"))
            ->latest()
            ->get();

        return view('diplomes.index', compact('diplomes'));
    }

    public function create(Request $request): View
    {
        $intervenants = Intervenant::forUser($request->user())->orderBy('nom')->get();

        return view('diplomes.create', compact('intervenants'));
    }

    public function store(StoreDiplomeRequest $request): RedirectResponse
    {
        Diplome::create($request->validated());

        return redirect()->route('diplomes.index')->with('success', 'Diplôme créé avec succès.');
    }

    public function show(Diplome $diplome): View
    {
        $diplome->load('intervenant');

        return view('diplomes.show', compact('diplome'));
    }

    public function edit(Request $request, Diplome $diplome): View
    {
        $intervenants = Intervenant::forUser($request->user())->orderBy('nom')->get();

        return view('diplomes.edit', compact('diplome', 'intervenants'));
    }

    public function update(UpdateDiplomeRequest $request, Diplome $diplome): RedirectResponse
    {
        $diplome->update($request->validated());

        return redirect()->route('diplomes.index')->with('success', 'Diplôme mis à jour avec succès.');
    }

    public function destroy(Diplome $diplome): RedirectResponse
    {
        $diplome->delete();

        return redirect()->route('diplomes.index')->with('success', 'Diplôme supprimé avec succès.');
    }
}
