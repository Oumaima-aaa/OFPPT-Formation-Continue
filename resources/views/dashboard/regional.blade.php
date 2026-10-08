@extends('layouts.app')



@section('title', 'Tableau de bord régional')



@php

    $header = 'Administration régionale';

    $subtitle = 'Statistiques de votre région';

@endphp



@section('content')

    <div class="row g-4 mb-4">

        <div class="col-md-6 col-xl-3">

            <x-stat-card label="Établissements" :value="$stats['etablissements']" icon="building" color="green" />

        </div>

        <div class="col-md-6 col-xl-3">

            <x-stat-card label="Actions" :value="$stats['actions']" icon="play-circle" color="blue" />

        </div>

        <div class="col-md-6 col-xl-3">

            <x-stat-card label="Plans" :value="$stats['plans']" icon="calendar-check" color="orange" />

        </div>

        <div class="col-md-6 col-xl-3">

            <x-stat-card label="Entreprises" :value="$stats['entreprises']" icon="briefcase" color="purple" />

        </div>

    </div>



    <div class="card table-card">

        <div class="card-header"><i class="bi bi-pie-chart"></i> Actions par statut</div>

        <div class="card-body p-0">

            @if($actionsByStatus->isNotEmpty())

                <div class="table-responsive">

                    <table class="table mb-0">

                        <thead>

                            <tr>

                                <th>Statut</th>

                                <th>Nombre</th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach($actionsByStatus as $status => $total)

                                <tr>

                                    <td>{{ $status }}</td>

                                    <td><strong>{{ $total }}</strong></td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="empty-state">

                    <i class="bi bi-inbox"></i>

                    <p>Aucune action dans cette région.</p>

                </div>

            @endif

        </div>

    </div>

@endsection


