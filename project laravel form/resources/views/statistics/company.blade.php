@extends('layouts.app')

@section('title', 'Mes statistiques')

@php
    $header = 'Mes statistiques';
    $subtitle = $scopeLabel ?? 'Indicateurs de vos formations';
@endphp

@section('content')
    <div class="alert alert-info py-2 mb-3"><i class="bi bi-funnel me-1"></i> Périmètre : <strong>{{ $scopeLabel }}</strong></div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card stat-card h-100"><div class="card-body"><div class="stat-label">Plans</div><div class="stat-value">{{ $summary['total_plans'] }}</div></div></div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card h-100"><div class="card-body"><div class="stat-label">Actions</div><div class="stat-value">{{ $summary['total_actions'] }}</div></div></div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card h-100"><div class="card-body"><div class="stat-label">Terminées</div><div class="stat-value">{{ $summary['completed_actions'] }}</div></div></div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card h-100"><div class="card-body"><div class="stat-label">Participants prévus</div><div class="stat-value">{{ $summary['planned_participants'] }}</div></div></div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card chart-card h-100">
                <div class="card-header"><i class="bi bi-graph-up"></i> Plans par année</div>
                <div class="card-body"><canvas id="plansByYearChart" height="200"></canvas></div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card chart-card h-100">
                <div class="card-header"><i class="bi bi-pie-chart"></i> Actions par statut</div>
                <div class="card-body"><canvas id="actionsByStatusChart" height="200"></canvas></div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card chart-card h-100">
                <div class="card-header"><i class="bi bi-trophy"></i> Thèmes les plus demandés</div>
                <div class="card-body"><canvas id="themesRequestedChart" height="160"></canvas></div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const colors = ['#007a3d', '#2563eb', '#ea580c', '#7c3aed', '#0d9488', '#dc2626', '#0891b2', '#ca8a04'];
    const plansByYear = @json($plansByYear);
    const actionsByStatus = @json($actionsByStatus);
    const themesRequested = @json($themesRequested);

    new window.Chart(document.getElementById('plansByYearChart'), {
        type: 'bar',
        data: { labels: Object.keys(plansByYear), datasets: [{ label: 'Plans', data: Object.values(plansByYear), backgroundColor: colors[0] }] },
        options: { responsive: true, maintainAspectRatio: false }
    });

    new window.Chart(document.getElementById('actionsByStatusChart'), {
        type: 'doughnut',
        data: { labels: Object.keys(actionsByStatus), datasets: [{ data: Object.values(actionsByStatus), backgroundColor: colors }] },
        options: { responsive: true, maintainAspectRatio: false }
    });

    new window.Chart(document.getElementById('themesRequestedChart'), {
        type: 'bar',
        data: { labels: Object.keys(themesRequested), datasets: [{ label: 'Demandes', data: Object.values(themesRequested), backgroundColor: colors[2] }] },
        options: { responsive: true, maintainAspectRatio: false, indexAxis: 'y' }
    });
});
</script>
@endpush
