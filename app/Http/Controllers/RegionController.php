<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRegionRequest;
use App\Http\Requests\UpdateRegionRequest;
use App\Models\Region;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RegionController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Region::class, 'region', ['except' => ['index', 'show']]);
    }

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Region::class);
        $regions = Region::forUser($request->user())
            ->with('user')
            ->when($request->search, fn ($q, $search) => $q->where('nom_region', 'like', "%{$search}%"))
            ->orderBy('nom_region')
            ->get();

        return view('regions.index', compact('regions'));
    }

    public function create(): View
    {
        $users = $this->regionalManagersForSelection();

        return view('regions.create', compact('users'));
    }

    public function store(StoreRegionRequest $request): RedirectResponse
    {
        $region = Region::create($request->validated());
        $this->syncRegionalManager($region, null, $request->validated('users_id'));

        return redirect()->route('regions.index')->with('success', 'Région créée avec succès.');
    }

    public function show(Region $region): View
    {
        $this->authorize('view', $region);

        $region->load(['etablissements', 'user']);

        return view('regions.show', compact('region'));
    }

    public function edit(Region $region): View
    {
        $users = $this->regionalManagersForSelection();

        return view('regions.edit', compact('region', 'users'));
    }

    /** @return \Illuminate\Database\Eloquent\Collection<int, User> */
    protected function regionalManagersForSelection()
    {
        return User::role(User::ROLE_REGIONAL_MANAGER)
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();
    }

    public function update(UpdateRegionRequest $request, Region $region): RedirectResponse
    {
        $previousUserId = $region->users_id;
        $region->update($request->validated());
        $this->syncRegionalManager($region, $previousUserId, $request->validated('users_id'));

        return redirect()->route('regions.index')->with('success', 'Région mise à jour avec succès.');
    }

    protected function syncRegionalManager(Region $region, ?int $previousUserId, ?int $newUserId): void
    {
        if ($previousUserId && (int) $previousUserId !== (int) $newUserId) {
            User::where('id', $previousUserId)->update(['region_id' => null]);
        }

        if ($newUserId) {
            Region::where('users_id', $newUserId)->where('id', '!=', $region->id)->update(['users_id' => null]);
            User::where('id', $newUserId)->update(['region_id' => $region->id]);
        }
    }

    public function destroy(Region $region): RedirectResponse
    {
        $region->delete();

        return redirect()->route('regions.index')->with('success', 'Région supprimée avec succès.');
    }
}
