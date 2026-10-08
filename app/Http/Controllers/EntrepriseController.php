<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEntrepriseRequest;
use App\Http\Requests\UpdateEntrepriseRequest;
use App\Models\Entreprise;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class EntrepriseController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Entreprise::class, 'entreprise');
    }

    public function index(Request $request): View
    {
        $entreprises = Entreprise::forUser($request->user())
            ->with('user')
            ->when($request->search, function ($q, $search) {
                $q->where('raison', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->latest()
            ->get();

        return view('entreprises.index', compact('entreprises'));
    }

    public function create(): View
    {
        $users = User::role(User::ROLE_COMPANY)->doesntHave('entreprise')->orderBy('first_name')->get();

        return view('entreprises.create', compact('users'));
    }

    public function store(StoreEntrepriseRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        Entreprise::create($data);

        return redirect()->route('entreprises.index')->with('success', 'Entreprise créée avec succès.');
    }

    public function show(Entreprise $entreprise): View
    {
        $entreprise->load(['user', 'actions.theme']);

        return view('entreprises.show', compact('entreprise'));
    }

    public function edit(Entreprise $entreprise): View
    {
        $users = User::role(User::ROLE_COMPANY)
            ->where(function ($q) use ($entreprise) {
                $q->doesntHave('entreprise')->orWhere('id', $entreprise->users_id);
            })
            ->orderBy('first_name')
            ->get();

        return view('entreprises.edit', compact('entreprise', 'users'));
    }

    public function update(UpdateEntrepriseRequest $request, Entreprise $entreprise): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            if ($entreprise->logo) {
                Storage::disk('public')->delete($entreprise->logo);
            }
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $entreprise->update($data);

        return redirect()->route('entreprises.index')->with('success', 'Entreprise mise à jour avec succès.');
    }

    public function destroy(Entreprise $entreprise): RedirectResponse
    {
        if ($entreprise->logo) {
            Storage::disk('public')->delete($entreprise->logo);
        }

        $entreprise->delete();

        return redirect()->route('entreprises.index')->with('success', 'Entreprise supprimée avec succès.');
    }
}
