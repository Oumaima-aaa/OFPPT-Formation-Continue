@extends('layouts.app')

@section('title', 'Tableau de bord')

@php
    $header = $entreprise->raison ?? 'Mon entreprise';
    $subtitle = 'Vue d\'ensemble de vos formations';
@endphp

@section('content')
    <div class="row g-3 mb-4">
        <div class="col-md-4 col-lg">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="stat-label">Plans de formation</div>
                    <div class="stat-value">{{ $stats['total_plans'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-lg">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="stat-label">Actions de formation</div>
                    <div class="stat-value">{{ $stats['total_actions'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-lg">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="stat-label">Actions terminées</div>
                    <div class="stat-value">{{ $stats['completed_actions'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-lg">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="stat-label">Actions en attente</div>
                    <div class="stat-value">{{ $stats['pending_actions'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-lg">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="stat-label">Participants prévus</div>
                    <div class="stat-value">{{ $stats['planned_participants'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-play-circle me-2"></i>Mes actions récentes</span>
                    <a href="{{ route('actions.index') }}" class="btn btn-sm btn-outline-primary">Voir tout</a>
                </div>
                <div class="card-body p-0">
                    @forelse($recentActions as $action)
                        <div class="list-group-item d-flex justify-content-between align-items-center py-3 px-3 border-bottom">
                            <div>
                                <strong>{{ $action->theme->intitule_theme ?? '—' }}</strong>
                                <div class="small text-muted">{{ $action->etablissement->nom_efp ?? '—' }} · {{ $action->date_debut->format('d/m/Y') }}</div>
                                <span class="badge bg-secondary mt-1">{{ $action->status_label }}</span>
                            </div>
                            <a href="{{ route('actions.show', $action) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a>
                        </div>
                    @empty
                        <div class="empty-state"><i class="bi bi-inbox"></i><p>Aucune action de formation.</p></div>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-bell me-2"></i>Notifications</span>
                    <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-outline-primary">Toutes</a>
                </div>
                <div class="card-body p-0">
                    @forelse($notifications as $notification)
                        <div class="list-group-item py-3 px-3 border-bottom">
                            <div class="fw-semibold">{{ $notification->data['message'] ?? 'Notification' }}</div>
                            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                        </div>
                    @empty
                        <div class="empty-state"><i class="bi bi-bell-slash"></i><p>Aucune notification non lue.</p></div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
