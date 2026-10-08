<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompetenceRequest;
use App\Http\Requests\UpdateCompetenceRequest;
use App\Models\Competence;
use App\Models\Intervenant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CompetenceController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Competence::class, 'competence');
    }

    public function index(Request $request): View
    {
        $competences = Competence::forUser($request->user())
            ->with('intervenant')
            ->when($request->search, fn ($q, $search) => $q->where('nom', 'like', "%{$search}%"))
            ->latest()
            ->get();

        return view('competences.index', compact('competences'));
    }

    public function create(Request $request): View
    {
        $intervenants = Intervenant::forUser($request->user())->orderBy('nom')->get();

        return view('competences.create', compact('intervenants'));
    }

    public function store(StoreCompetenceRequest $request): RedirectResponse
    {
        Competence::create($request->validated());

        return redirect()->route('competences.index')->with('success', 'Compétence créée avec succès.');
    }

    public function show(Competence $competence): View
    {
        $competence->load('intervenant');

        return view('competences.show', compact('competence'));
    }

    public function edit(Request $request, Competence $competence): View
    {
        $intervenants = Intervenant::forUser($request->user())->orderBy('nom')->get();

        return view('competences.edit', compact('competence', 'intervenants'));
    }

    public function update(UpdateCompetenceRequest $request, Competence $competence): RedirectResponse
    {
        $competence->update($request->validated());

        return redirect()->route('competences.index')->with('success', 'Compétence mise à jour avec succès.');
    }

    public function destroy(Competence $competence): RedirectResponse
    {
        $competence->delete();

        return redirect()->route('competences.index')->with('success', 'Compétence supprimée avec succès.');
    }
}
