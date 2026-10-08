<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreIntervenantRequest;
use App\Http\Requests\UpdateIntervenantRequest;
use App\Models\Etablissement;
use App\Models\Intervenant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IntervenantController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Intervenant::class, 'intervenant');
    }

    public function index(Request $request): View|RedirectResponse
    {
        if ($request->user()->isTrainer()) {
            $intervenant = $request->user()->intervenant;
            abort_unless($intervenant, 403, 'Aucun profil intervenant associé à ce compte.');

            return redirect()->route('intervenants.edit', $intervenant);
        }

        $intervenants = Intervenant::forUser($request->user())
            ->with(['etablissement', 'competences', 'diplomes', 'certifications'])
            ->withCount(['competences', 'diplomes', 'certifications'])
            ->when($request->search, function ($q, $search) {
                $q->where('nom', 'like', "%{$search}%")
                    ->orWhere('prenom', 'like', "%{$search}%")
                    ->orWhere('matricule', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->latest()
            ->get();

        return view('intervenants.index', compact('intervenants'));
    }

    public function create(Request $request): View
    {
        $etablissements = Etablissement::forUser($request->user())
            ->where('status', Etablissement::STATUS_ACTIF)
            ->orderBy('nom_efp')
            ->get();

        return view('intervenants.create', compact('etablissements'));
    }

    public function store(StoreIntervenantRequest $request): RedirectResponse
    {
        Intervenant::create($request->validated());

        return redirect()->route('intervenants.index')->with('success', 'Intervenant créé avec succès.');
    }

    public function show(Intervenant $intervenant): View
    {
        $intervenant->load(['etablissement', 'competences', 'diplomes', 'certifications']);

        return view('intervenants.show', compact('intervenant'));
    }

    public function edit(Request $request, Intervenant $intervenant): View
    {
        $etablissements = $request->user()->isTrainer()
            ? collect()
            : Etablissement::forUser($request->user())
                ->where('status', Etablissement::STATUS_ACTIF)
                ->orderBy('nom_efp')
                ->get();

        return view('intervenants.edit', compact('intervenant', 'etablissements'));
    }

    public function update(UpdateIntervenantRequest $request, Intervenant $intervenant): RedirectResponse
    {
        $except = $request->user()->isTrainer()
            ? ['matricule', 'etablissements_id', 'type_intervenant']
            : [];

        $intervenant->update($request->safe()->except($except));

        $redirect = $request->user()->isTrainer()
            ? redirect()->route('intervenants.edit', $intervenant)
            : redirect()->route('intervenants.index');

        return $redirect->with('success', 'Profil intervenant mis à jour avec succès.');
    }

    public function destroy(Intervenant $intervenant): RedirectResponse
    {
        $intervenant->delete();

        return redirect()->route('intervenants.index')->with('success', 'Intervenant supprimé avec succès.');
    }
}
