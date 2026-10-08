@extends('layouts.app')

@section('title', 'Régions')

@php
    $header = 'Régions';
    $subtitle = 'Gestion des régions du réseau OFPPT';
    $headerActions = '<a href="' . route('regions.create') . '" class="btn btn-primary btn-icon"><i class="bi bi-plus-lg"></i> Nouvelle région</a>';
@endphp

@section('content')
    @include('partials.search')

    <div class="card table-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-geo-alt me-2"></i>Liste des régions</span>
            <span class="badge bg-light text-dark">{{ $regions->count() }} résultat(s)</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nom de la région</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($regions as $region)
                            <tr>
                                <td><span class="text-muted">#{{ $region->id }}</span></td>
                                <td><strong>{{ $region->nom_region }}</strong></td>
                                <td>
                                    <div class="table-actions">
                                        <a href="{{ route('regions.show', $region) }}" class="btn btn-sm btn-outline-secondary btn-icon"><i class="bi bi-eye"></i> Voir</a>
                                        <a href="{{ route('regions.edit', $region) }}" class="btn btn-sm btn-outline-primary btn-icon"><i class="bi bi-pencil"></i> Modifier</a>
                                        <form action="{{ route('regions.destroy', $region) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger btn-icon"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">
                                    <div class="empty-state">
                                        <i class="bi bi-inbox"></i>
                                        <p>Aucune région trouvée.</p>
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
