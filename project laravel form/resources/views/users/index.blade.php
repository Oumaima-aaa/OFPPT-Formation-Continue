@extends('layouts.app')

@section('title', 'Utilisateurs')

@php
    use App\Models\User;
    $header = 'Utilisateurs';
    $subtitle = 'Gestion des comptes et des accès';
    $headerActions = auth()->user()->can('create', User::class)
        ? '<a href="' . route('users.create') . '" class="btn btn-primary btn-icon"><i class="bi bi-person-plus"></i> Nouvel utilisateur</a>'
        : '';
@endphp

@section('content')
    <form method="GET" class="search-bar mb-3">
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small text-muted mb-1">Recherche</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Nom, e-mail, téléphone..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label small text-muted mb-1">Rôle</label>
                <select name="role_id" class="form-select">
                    <option value="">Tous les rôles</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" @selected((int) request('role_id') === $role->id)>{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted mb-1">Statut</label>
                <select name="status" class="form-select">
                    <option value="">Tous</option>
                    @foreach(User::statusLabels() as $value => $label)
                        <option value="{{ $value }}" @selected((string) request('status') === (string) $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary btn-icon"><i class="bi bi-funnel"></i> Filtrer</button>
                @if(request()->hasAny(['search', 'role_id', 'status']))
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Réinitialiser</a>
                @endif
            </div>
        </div>
    </form>

    <div class="card table-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-people me-2"></i>Liste des utilisateurs</span>
            <span class="badge bg-light text-dark">{{ $users->count() }} résultat(s)</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nom complet</th>
                            <th>E-mail</th>
                            <th>Téléphone</th>
                            <th>Rôle</th>
                            <th>Statut</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td><span class="text-muted">#{{ $user->id }}</span></td>
                                <td><strong>{{ $user->full_name }}</strong></td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone ?? '—' }}</td>
                                <td><span class="badge bg-primary-subtle text-primary-emphasis">{{ $user->assignedRole?->name ?? '—' }}</span></td>
                                <td>
                                    @if($user->isActive())
                                        <span class="badge bg-success-subtle text-success">Actif</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary">Inactif</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="table-actions justify-content-end">
                                        @can('view', $user)
                                            <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-outline-secondary btn-icon"><i class="bi bi-eye"></i></a>
                                        @endcan
                                        @can('update', $user)
                                            <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-primary btn-icon"><i class="bi bi-pencil"></i></a>
                                        @endcan
                                        @can('delete', $user)
                                            <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger btn-icon"><i class="bi bi-trash"></i></button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <i class="bi bi-inbox"></i>
                                        <p>Aucun utilisateur trouvé.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
