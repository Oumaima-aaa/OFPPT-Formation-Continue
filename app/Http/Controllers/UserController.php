<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResetUserPasswordRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    public function index(Request $request): View
    {
        $users = User::forUser($request->user())
            ->with(['assignedRole', 'assignedRegion', 'assignedEstablishment'])
            ->search($request->input('search'))
            ->byRole($request->integer('role_id') ?: null)
            ->when($request->filled('status') && in_array((int) $request->status, [User::STATUS_ACTIVE, User::STATUS_INACTIVE], true), function ($q) use ($request) {
                $q->where('status', (int) $request->status);
            })
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        $roles = Role::orderBy('name')->get();

        return view('users.index', compact('users', 'roles'));
    }

    public function create(): View
    {
        $roles = Role::orderBy('name')->get();

        return view('users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $data = $request->safe()->except(['password', 'password_confirmation']);
        $data['password'] = $request->validated('password');

        $user = User::create($data);
        $user->assignSingleRole((int) $request->validated('role_id'));

        return redirect()->route('users.index')->with('success', 'Utilisateur créé avec succès.');
    }

    public function show(User $user): View
    {
        $user->load(['assignedRole', 'assignedRegion', 'assignedEstablishment', 'entreprise']);

        return view('users.show', compact('user'));
    }

    public function edit(User $user): View
    {
        $roles = Role::orderBy('name')->get();

        return view('users.edit', compact('user', 'roles'));
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $data = $request->safe()->except(['password', 'password_confirmation']);

        if ($request->filled('password')) {
            $data['password'] = $request->validated('password');
        }

        $user->update($data);
        $user->assignSingleRole((int) $request->validated('role_id'));

        return redirect()->route('users.index')->with('success', 'Utilisateur mis à jour avec succès.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Utilisateur supprimé avec succès.');
    }

    public function activate(User $user): RedirectResponse
    {
        $this->authorize('activate', $user);

        $user->update(['status' => User::STATUS_ACTIVE]);

        return back()->with('success', 'Compte activé avec succès.');
    }

    public function deactivate(User $user): RedirectResponse
    {
        $this->authorize('deactivate', $user);

        $user->update(['status' => User::STATUS_INACTIVE]);

        return back()->with('success', 'Compte désactivé avec succès.');
    }

    public function resetPassword(ResetUserPasswordRequest $request, User $user): RedirectResponse
    {
        $user->update(['password' => $request->validated('password')]);

        return back()->with('success', 'Mot de passe réinitialisé avec succès.');
    }
}
