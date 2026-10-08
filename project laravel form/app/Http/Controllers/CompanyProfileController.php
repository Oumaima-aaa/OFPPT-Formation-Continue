<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateCompanyProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CompanyProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            abort_unless($request->user()?->isCompany(), 403);

            return $next($request);
        });
    }

    public function edit(Request $request): View
    {
        $user = $request->user();
        $entreprise = $user->entreprise;

        abort_unless($entreprise, 403, 'Aucune entreprise associée à ce compte.');

        return view('company.profile', compact('user', 'entreprise'));
    }

    public function update(UpdateCompanyProfileRequest $request): RedirectResponse
    {
        $entreprise = $request->user()->entreprise;
        $entreprise->update($request->validatedEntreprise());

        $request->user()->update($request->validatedUser());

        return back()->with('success', 'Profil entreprise mis à jour avec succès.');
    }
}
