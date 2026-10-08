@extends('layouts.app')

@section('title', $domaine->nom_domaine)

@php
    $header = $domaine->nom_domaine;
    $subtitle = 'Thèmes de formation disponibles';
    $headerActions = '<a href="' . route('catalog.index') . '" class="btn btn-outline-secondary">Retour au catalogue</a>';
@endphp

@section('content')
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Thème</th>
                            <th>Durée</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($themes as $theme)
                            <tr>
                                <td>{{ $theme->intitule_theme }}</td>
                                <td>{{ $theme->duree_formation }} jours</td>
                                <td class="text-end">
                                    <a href="{{ route('catalog.themes.show', $theme) }}" class="btn btn-sm btn-outline-primary">Voir</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted py-4">Aucun thème actif dans ce domaine.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
