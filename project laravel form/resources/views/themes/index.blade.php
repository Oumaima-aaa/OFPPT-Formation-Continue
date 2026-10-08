@extends('layouts.app')



@section('title', 'Thèmes')



@php

    use App\Models\Theme;

    $header = 'Thèmes de formation';

    $subtitle = 'Catalogue des programmes proposés par domaine';

    $headerActions = auth()->user()->can('create', Theme::class)
        ? '<a href="' . route('themes.create') . '" class="btn btn-primary btn-icon"><i class="bi bi-plus-lg"></i> Nouveau thème</a>'
        : null;

@endphp



@section('content')

    <form method="GET" class="search-bar">

        <div class="row g-2 align-items-end">

            <div class="col-md-4">

                <label class="form-label small text-muted mb-1">Recherche</label>

                <div class="input-group">

                    <span class="input-group-text"><i class="bi bi-search"></i></span>

                    <input type="text" name="search" class="form-control" placeholder="Intitulé ou domaine..." value="{{ request('search') }}">

                </div>

            </div>

            <div class="col-md-3">

                <label class="form-label small text-muted mb-1">Domaine</label>

                <select name="domaines_id" class="form-select">

                    <option value="">Tous les domaines</option>

                    @foreach($domaines as $domaine)

                        <option value="{{ $domaine->id }}" @selected(request('domaines_id') == $domaine->id)>{{ $domaine->nom_domaine }}</option>

                    @endforeach

                </select>

            </div>

            <div class="col-md-2">

                <label class="form-label small text-muted mb-1">Statut</label>

                <select name="status" class="form-select">

                    <option value="">Tous</option>

                    @foreach(Theme::activeStatusLabels() as $value => $label)

                        <option value="{{ $value }}" @selected((string) request('status') === (string) $value)>{{ $label }}</option>

                    @endforeach

                </select>

            </div>

            <div class="col-md-3 d-flex gap-2">

                <button type="submit" class="btn btn-primary btn-icon flex-grow-1"><i class="bi bi-funnel"></i> Filtrer</button>

                @if(request()->hasAny(['search', 'domaines_id', 'status']))

                    <a href="{{ route('themes.index') }}" class="btn btn-outline-secondary">Reset</a>

                @endif

            </div>

        </div>

    </form>



    <div class="card table-card">

        <div class="card-header d-flex justify-content-between align-items-center">

            <span><i class="bi bi-book me-2"></i>Liste des thèmes</span>

            <span class="badge bg-light text-dark">{{ $themes->count() }} thème(s)</span>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table align-middle mb-0">

                    <thead>

                        <tr>

                            <th>#</th>

                            <th>Intitulé</th>

                            <th>Domaine</th>

                            <th>Durée</th>

                            <th>Plans</th>

                            <th>Statut</th>

                            <th class="text-end">Actions</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($themes as $theme)

                            <tr>

                                <td><span class="text-muted">#{{ $theme->id }}</span></td>

                                <td>

                                    <strong>{{ $theme->intitule_theme }}</strong>

                                </td>

                                <td>

                                    <span class="badge bg-light text-dark border">

                                        <i class="bi bi-grid-1x2 me-1"></i>{{ $theme->domaine->nom_domaine ?? '—' }}

                                    </span>

                                </td>

                                <td>{{ $theme->duree_formation }} j</td>

                                <td><span class="badge bg-light text-dark">{{ $theme->plans_count ?? 0 }}</span></td>

                                <td>

                                    <span class="badge bg-{{ $theme->isActive() ? 'success' : 'secondary' }}">

                                        {{ $theme->status_label }}

                                    </span>

                                </td>

                                <td>

                                    <div class="table-actions">

                                        <a href="{{ route('themes.show', $theme) }}" class="btn btn-sm btn-outline-secondary btn-icon" title="Voir"><i class="bi bi-eye"></i></a>
                                        @can('update', $theme)
                                        <a href="{{ route('themes.edit', $theme) }}" class="btn btn-sm btn-outline-primary btn-icon" title="Modifier"><i class="bi bi-pencil"></i></a>
                                        @endcan
                                        @can('delete', $theme)
                                        <form action="{{ route('themes.destroy', $theme) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ce thème ?');">

                                            @csrf

                                            @method('DELETE')

                                            <button type="submit" class="btn btn-sm btn-outline-danger btn-icon" title="Supprimer"><i class="bi bi-trash"></i></button>

                                        </form>
                                        @endcan

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7">

                                    <div class="empty-state">

                                        <i class="bi bi-book"></i>

                                        <p>Aucun thème trouvé.</p>
                                        @can('create', Theme::class)
                                        <a href="{{ route('themes.create') }}" class="btn btn-sm btn-primary">Créer un thème</a>
                                        @endcan

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


