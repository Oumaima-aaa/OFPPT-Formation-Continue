@extends('layouts.app')

@section('title', 'Tableau de bord formateur')

@php
    $header = 'Tableau de bord formateur';
@endphp

@section('content')
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="stat-label">Compétences</div>
                    <div class="stat-value">{{ $stats['competences'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="stat-label">Diplômes</div>
                    <div class="stat-value">{{ $stats['diplomes'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="stat-label">Certifications</div>
                    <div class="stat-value">{{ $stats['certifications'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="stat-label">Actions assignées</div>
                    <div class="stat-value">{{ $stats['actions'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Mes actions récentes</span>
            <a href="{{ route('actions.index') }}" class="btn btn-sm btn-outline-primary">Voir tout</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Thème</th>
                        <th>Établissement</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentActions as $action)
                        <tr>
                            <td><a href="{{ route('actions.show', $action) }}">{{ $action->theme->intitule_theme ?? '—' }}</a></td>
                            <td>{{ $action->etablissement->nom_efp ?? '—' }}</td>
                            <td><span class="badge bg-secondary">{{ $action->status_label }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-muted">Aucune action assignée.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('intervenants.edit', $intervenant) }}" class="btn btn-primary">Mon profil</a>
        <a href="{{ route('competences.index') }}" class="btn btn-outline-primary">Mes compétences</a>
        <a href="{{ route('diplomes.index') }}" class="btn btn-outline-primary">Mes diplômes</a>
        <a href="{{ route('certifications.index') }}" class="btn btn-outline-primary">Mes certifications</a>
    </div>
@endsection
