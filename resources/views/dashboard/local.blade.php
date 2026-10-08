@extends('layouts.app')



@section('title', 'Tableau de bord local')



@php

    $header = 'Administration locale';

    $subtitle = 'Gestion de votre établissement';

@endphp



@section('content')

    <div class="row g-4 mb-4">

        <div class="col-md-4">

            <x-stat-card label="Plans de formation" :value="$stats['plans']" icon="calendar-check" color="orange" :link="route('plans.index')" />

        </div>

        <div class="col-md-4">

            <x-stat-card label="Actions de formation" :value="$stats['actions']" icon="play-circle" color="green" :link="route('actions.index')" />

        </div>

        <div class="col-md-4">

            <x-stat-card label="Intervenants" :value="$stats['intervenants']" icon="people" color="blue" :link="route('intervenants.index')" />

        </div>

    </div>



    <div class="card table-card">

        <div class="card-header d-flex justify-content-between align-items-center">

            <span><i class="bi bi-clock-history me-2"></i>Plans récents</span>

            <a href="{{ route('plans.index') }}" class="btn btn-sm btn-outline-primary">Tous les plans</a>

        </div>

        <div class="card-body p-0">

            @if($recentPlans->isNotEmpty())

                <div class="table-responsive">

                    <table class="table mb-0">

                        <thead>

                            <tr><th>Exercice</th><th>Thème</th><th>Statut</th><th></th></tr>

                        </thead>

                        <tbody>

                            @foreach($recentPlans as $plan)

                                <tr>

                                    <td>{{ $plan->exercice }}</td>

                                    <td>{{ $plan->theme->intitule_theme ?? '—' }}</td>

                                    <td><span class="badge bg-secondary">{{ $plan->status_label }}</span></td>

                                    <td class="text-end"><a href="{{ route('plans.show', $plan) }}" class="btn btn-sm btn-outline-secondary btn-icon"><i class="bi bi-eye"></i></a></td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="empty-state"><i class="bi bi-inbox"></i><p>Aucun plan récent.</p></div>

            @endif

        </div>

    </div>

@endsection


