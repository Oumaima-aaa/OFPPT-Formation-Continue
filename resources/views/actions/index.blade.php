@extends('layouts.app')

@section('title', auth()->user()->isCompany() ? 'Mes actions de formation' : 'Actions de formation')

@php
    use App\Models\Action;
    $header = auth()->user()->isCompany() ? 'Mes actions de formation' : 'Actions de formation';
    $headerActions = auth()->user()->can('create', Action::class)
        ? '<a href="' . route('actions.create') . '" class="btn btn-primary">' . (auth()->user()->isCompany() ? 'Nouvelle demande' : 'Nouvelle action') . '</a>'
        : null;
@endphp

@section('content')
    <form method="GET" class="search-bar mb-3">
        <div class="row g-2">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Rechercher un thème..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-4">
                <select name="status" class="form-select">
                    <option value="">Tous les statuts</option>
                    @foreach(Action::statusLabels() as $value => $label)
                        <option value="{{ $value }}" @selected(request('status') !== null && (int) request('status') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">Filtrer</button>
                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('actions.index') }}" class="btn btn-outline-secondary">Réinitialiser</a>
                @endif
            </div>
        </div>
    </form>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Exercice</th>
                            <th>Thème</th>
                            @unless(auth()->user()->isCompany())
                                <th>Entreprise</th>
                            @endunless
                            <th>Établissement</th>
                            <th>Début</th>
                            <th>Fin</th>
                            <th>Prix réel</th>
                            <th>Statut</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($actions as $action)
                            <tr>
                                <td>{{ $action->id }}</td>
                                <td>{{ $action->exercice }}</td>
                                <td>{{ $action->theme->intitule_theme ?? '—' }}</td>
                                @unless(auth()->user()->isCompany())
                                    <td>{{ $action->entreprise->raison ?? '—' }}</td>
                                @endunless
                                <td>{{ $action->etablissement->nom_efp ?? '—' }}</td>
                                <td>{{ $action->date_debut->format('d/m/Y') }}</td>
                                <td>{{ $action->date_fin->format('d/m/Y') }}</td>
                                <td>{{ number_format($action->prix_reel, 2, ',', ' ') }} DH</td>
                                <td><span class="badge bg-secondary">{{ $action->status_label }}</span></td>
                                <td class="text-end">
                                    <a href="{{ route('actions.show', $action) }}" class="btn btn-sm btn-outline-info">Voir</a>
                                    @can('update', $action)
                                        <a href="{{ route('actions.edit', $action) }}" class="btn btn-sm btn-outline-primary">Modifier</a>
                                    @endcan
                                    @can('cancel', $action)
                                        <form action="{{ route('actions.cancel', $action) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer l\'annulation ?');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-warning">Annuler</button>
                                        </form>
                                    @endcan
                                    @can('delete', $action)
                                        @unless(auth()->user()->isCompany())
                                            <form action="{{ route('actions.destroy', $action) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                                            </form>
                                        @endunless
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="{{ auth()->user()->isCompany() ? 9 : 10 }}" class="text-center text-muted">Aucune action trouvée.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
