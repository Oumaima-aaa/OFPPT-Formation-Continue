@extends('layouts.app')

@section('title', 'Catalogue formations')

@php
    $header = 'Catalogue formations';
    $subtitle = 'Consultez les domaines et thèmes disponibles';
@endphp

@section('content')
    <form method="GET" class="search-bar mb-4">
        <div class="row g-2">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Rechercher un domaine ou thème..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-4">
                <select name="domaines_id" class="form-select">
                    <option value="">Tous les domaines</option>
                    @foreach($allDomaines as $domaine)
                        <option value="{{ $domaine->id }}" @selected(request('domaines_id') == $domaine->id)>{{ $domaine->nom_domaine }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="regions_id" class="form-select">
                    <option value="">Offre globale</option>
                    @foreach($regions as $region)
                        <option value="{{ $region->id }}" @selected(request('regions_id') == $region->id)>Offre régionale — {{ $region->nom_region }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1"><i class="bi bi-funnel"></i> Filtrer</button>
                @if(request()->hasAny(['search', 'domaines_id', 'regions_id']))
                    <a href="{{ route('catalog.index') }}" class="btn btn-outline-secondary">Réinitialiser</a>
                @endif
            </div>
        </div>
    </form>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card h-100">
                <div class="card-header"><i class="bi bi-grid me-2"></i>Domaines</div>
                <div class="list-group list-group-flush">
                    @forelse($domaines as $domaine)
                        <a href="{{ route('catalog.domaines.show', $domaine) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>{{ $domaine->nom_domaine }}</span>
                            <span class="badge bg-primary rounded-pill">{{ $domaine->themes_count }}</span>
                        </a>
                    @empty
                        <div class="empty-state p-4"><i class="bi bi-inbox"></i><p>Aucun domaine trouvé.</p></div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card h-100">
                <div class="card-header"><i class="bi bi-book me-2"></i>Thèmes de formation</div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Thème</th>
                                    <th>Domaine</th>
                                    <th>Durée</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($themes as $theme)
                                    <tr>
                                        <td>{{ $theme->intitule_theme }}</td>
                                        <td>{{ $theme->domaine->nom_domaine ?? '—' }}</td>
                                        <td>{{ $theme->duree_formation }} j.</td>
                                        <td class="text-end">
                                            <a href="{{ route('catalog.themes.show', $theme) }}" class="btn btn-sm btn-outline-primary">Détails</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center text-muted py-4">Aucun thème trouvé.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
