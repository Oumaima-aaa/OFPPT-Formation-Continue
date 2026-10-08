<?php

namespace App\Http\Controllers;

use App\Models\Domaine;
use App\Models\Theme;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function index(Request $request): View
    {
        $query = Domaine::query()
            ->where('status', Domaine::STATUS_ACTIF);

        $domaines = (clone $query)
            ->when($request->search, fn ($q, $s) => $q->where('nom_domaine', 'like', "%{$s}%"))
            ->withCount(['themes' => fn ($q) => $q->where('status', Theme::STATUS_ACTIF)])
            ->orderBy('nom_domaine')
            ->get();

        $themesQuery = Theme::with('domaine')->where('status', Theme::STATUS_ACTIF);

        if ($request->filled('regions_id')) {
            $regionId = (int) $request->regions_id;
            $themesQuery->whereHas('plans.etablissement', fn ($q) => $q->where('regions_id', $regionId));
        }

        $themes = $themesQuery
            ->when($request->search, function ($q, $search) {
                $q->where(function ($query) use ($search) {
                    $query->where('intitule_theme', 'like', "%{$search}%")
                        ->orWhereHas('domaine', fn ($dq) => $dq->where('nom_domaine', 'like', "%{$search}%"));
                });
            })
            ->when($request->domaines_id, fn ($q, $id) => $q->where('domaines_id', $id))
            ->orderBy('intitule_theme')
            ->get();

        $allDomaines = Domaine::where('status', Domaine::STATUS_ACTIF)->orderBy('nom_domaine')->get();
        $regions = \App\Models\Region::orderBy('nom_region')->get();
        $offerType = $request->filled('regions_id') ? 'regional' : 'global';

        return view('catalog.index', compact('domaines', 'themes', 'allDomaines', 'regions', 'offerType'));
    }

    public function showDomaine(Domaine $domaine): View
    {
        abort_unless((int) $domaine->status === Domaine::STATUS_ACTIF, 404);

        $themes = $domaine->themes()
            ->where('status', Theme::STATUS_ACTIF)
            ->orderBy('intitule_theme')
            ->get();

        return view('catalog.domaine', compact('domaine', 'themes'));
    }

    public function showTheme(Theme $theme): View
    {
        abort_unless((int) $theme->status === Theme::STATUS_ACTIF, 404);

        $theme->load('domaine');

        return view('catalog.theme', compact('theme'));
    }
}
