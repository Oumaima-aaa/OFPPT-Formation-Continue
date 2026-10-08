<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCertificationRequest;
use App\Http\Requests\UpdateCertificationRequest;
use App\Models\Certification;
use App\Models\Intervenant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CertificationController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Certification::class, 'certification');
    }

    public function index(Request $request): View
    {
        $certifications = Certification::forUser($request->user())
            ->with('intervenant')
            ->when($request->search, fn ($q, $search) => $q->where('intitule', 'like', "%{$search}%"))
            ->latest()
            ->get();

        return view('certifications.index', compact('certifications'));
    }

    public function create(Request $request): View
    {
        $intervenants = Intervenant::forUser($request->user())->orderBy('nom')->get();

        return view('certifications.create', compact('intervenants'));
    }

    public function store(StoreCertificationRequest $request): RedirectResponse
    {
        Certification::create($request->validated());

        return redirect()->route('certifications.index')->with('success', 'Certification créée avec succès.');
    }

    public function show(Certification $certification): View
    {
        $certification->load('intervenant');

        return view('certifications.show', compact('certification'));
    }

    public function edit(Request $request, Certification $certification): View
    {
        $intervenants = Intervenant::forUser($request->user())->orderBy('nom')->get();

        return view('certifications.edit', compact('certification', 'intervenants'));
    }

    public function update(UpdateCertificationRequest $request, Certification $certification): RedirectResponse
    {
        $certification->update($request->validated());

        return redirect()->route('certifications.index')->with('success', 'Certification mise à jour avec succès.');
    }

    public function destroy(Certification $certification): RedirectResponse
    {
        $certification->delete();

        return redirect()->route('certifications.index')->with('success', 'Certification supprimée avec succès.');
    }
}
