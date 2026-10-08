@extends('layouts.app')

@section('title', 'Statistiques')

@php
    $header = 'Statistiques';
    $subtitle = $scopeLabel ?? 'Analyses et indicateurs de performance';
@endphp

@section('content')
    @isset($scopeLabel)
        <div class="alert alert-info py-2 mb-3"><i class="bi bi-funnel me-1"></i> Périmètre : <strong>{{ $scopeLabel }}</strong></div>
    @endisset
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card chart-card h-100">
                <div class="card-header"><i class="bi bi-graph-up"></i> Plans de formation par année</div>
                <div class="card-body">
                    <canvas id="plansByYearChart" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card chart-card h-100">
                <div class="card-header"><i class="bi bi-map"></i> Actions par région</div>
                <div class="card-body">
                    <canvas id="actionsByRegionChart" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card chart-card h-100">
                <div class="card-header"><i class="bi bi-trophy"></i> Thèmes les plus demandés</div>
                <div class="card-body">
                    <canvas id="themesRequestedChart" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card chart-card h-100">
                <div class="card-header"><i class="bi bi-building"></i> Entreprises par région</div>
                <div class="card-body">
                    <canvas id="entreprisesByRegionChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const plansByYear = @json($plansByYear);
    const actionsByRegion = @json($actionsByRegion);
    const themesRequested = @json($themesRequested);
    const entreprisesByRegion = @json($entreprisesByRegion);

    const colors = ['#007a3d', '#2563eb', '#ea580c', '#7c3aed', '#0d9488', '#dc2626', '#0891b2', '#ca8a04'];

    new window.Chart(document.getElementById('plansByYearChart'), {
        type: 'bar',
        data: {
            labels: Object.keys(plansByYear),
            datasets: [{
                label: 'Plans',
                data: Object.values(plansByYear),
                backgroundColor: colors[0],
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });

    new window.Chart(document.getElementById('actionsByRegionChart'), {
        type: 'bar',
        data: {
            labels: Object.keys(actionsByRegion),
            datasets: [{
                label: 'Actions',
                data: Object.values(actionsByRegion),
                backgroundColor: colors[1],
            }]
        },
        options: { responsive: true, maintainAspectRatio: false, indexAxis: 'y' }
    });

    new window.Chart(document.getElementById('themesRequestedChart'), {
        type: 'bar',
        data: {
            labels: Object.keys(themesRequested),
            datasets: [{
                label: 'Demandes',
                data: Object.values(themesRequested),
                backgroundColor: colors[2],
            }]
        },
        options: { responsive: true, maintainAspectRatio: false, indexAxis: 'y' }
    });

    new window.Chart(document.getElementById('entreprisesByRegionChart'), {
        type: 'doughnut',
        data: {
            labels: Object.keys(entreprisesByRegion),
            datasets: [{
                label: 'Entreprises',
                data: Object.values(entreprisesByRegion),
                backgroundColor: colors,
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });
});
</script>
@endpush
