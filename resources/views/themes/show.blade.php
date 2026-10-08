@extends('layouts.app')



@section('title', $theme->intitule_theme)



@php

    $header = $theme->intitule_theme;

    $subtitle = ($theme->domaine->nom_domaine ?? '—') . ' · ' . $theme->duree_formation . ' jours';

    $headerActions = '

        <a href="' . route('themes.edit', $theme) . '" class="btn btn-primary btn-icon"><i class="bi bi-pencil"></i> Modifier</a>

        <a href="' . route('themes.index') . '" class="btn btn-outline-secondary btn-icon"><i class="bi bi-arrow-left"></i> Retour</a>

    ';

@endphp



@section('content')

    <div class="row g-4">

        <div class="col-lg-4">

            <div class="card h-100">

                <div class="card-header"><i class="bi bi-info-circle me-2"></i>Détails</div>

                <div class="card-body">

                    <dl class="detail-list mb-0">

                        <dt>Intitulé</dt>

                        <dd>{{ $theme->intitule_theme }}</dd>



                        <dt>Domaine</dt>

                        <dd>

                            <span class="badge bg-light text-dark border">

                                <i class="bi bi-grid-1x2 me-1"></i>{{ $theme->domaine->nom_domaine ?? '—' }}

                            </span>

                        </dd>



                        <dt>Durée de formation</dt>

                        <dd>{{ $theme->duree_formation }} jours</dd>



                        <dt>Statut</dt>

                        <dd>

                            <span class="badge bg-{{ $theme->isActive() ? 'success' : 'secondary' }}">

                                {{ $theme->status_label }}

                            </span>

                        </dd>



                        <dt>Créé le</dt>

                        <dd>{{ $theme->created_at->format('d/m/Y H:i') }}</dd>

                    </dl>

                </div>

            </div>

        </div>



        <div class="col-lg-8">

            <div class="row g-4">

                <div class="col-md-6">

                    <x-stat-card

                        label="Plans associés"

                        :value="$theme->plans->count()"

                        icon="calendar-check"

                        color="orange"

                        :link="route('plans.index', ['search' => $theme->intitule_theme])"

                    />

                </div>

                <div class="col-md-6">

                    <x-stat-card

                        label="Actions associées"

                        :value="$theme->actions->count()"

                        icon="play-circle"

                        color="blue"

                        :link="route('actions.index', ['search' => $theme->intitule_theme])"

                    />

                </div>

            </div>



            <div class="card table-card mt-4">

                <div class="card-header"><i class="bi bi-calendar-check me-2"></i>Plans de formation</div>

                <div class="card-body p-0">

                    @if($theme->plans->isNotEmpty())

                        <div class="table-responsive">

                            <table class="table mb-0">

                                <thead>

                                    <tr>

                                        <th>Établissement</th>

                                        <th>Statut</th>

                                        <th></th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach($theme->plans as $plan)

                                        <tr>

                                            <td>{{ $plan->etablissement->nom_efp ?? '—' }}</td>

                                            <td><span class="badge bg-secondary">{{ $plan->status_label }}</span></td>

                                            <td class="text-end">

                                                <a href="{{ route('plans.show', $plan) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a>

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                        <div class="empty-state py-4">

                            <i class="bi bi-inbox"></i>

                            <p class="mb-0">Aucun plan associé à ce thème.</p>

                        </div>

                    @endif

                </div>

            </div>



            <div class="card table-card mt-4">

                <div class="card-header"><i class="bi bi-play-circle me-2"></i>Actions de formation</div>

                <div class="card-body p-0">

                    @if($theme->actions->isNotEmpty())

                        <div class="table-responsive">

                            <table class="table mb-0">

                                <thead>

                                    <tr>

                                        <th>Entreprise</th>

                                        <th>Période</th>

                                        <th>Statut</th>

                                        <th></th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach($theme->actions as $action)

                                        <tr>

                                            <td>{{ $action->entreprise->raison ?? '—' }}</td>

                                            <td>{{ $action->date_debut->format('d/m/Y') }} — {{ $action->date_fin->format('d/m/Y') }}</td>

                                            <td><span class="badge bg-secondary">{{ $action->status_label }}</span></td>

                                            <td class="text-end">

                                                <a href="{{ route('actions.show', $action) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a>

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                        <div class="empty-state py-4">

                            <i class="bi bi-inbox"></i>

                            <p class="mb-0">Aucune action associée à ce thème.</p>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>



    <div class="mt-4 pt-3 border-top">

        <form action="{{ route('themes.destroy', $theme) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer définitivement ce thème ?');">

            @csrf

            @method('DELETE')

            <button type="submit" class="btn btn-outline-danger btn-icon"><i class="bi bi-trash"></i> Supprimer le thème</button>

        </form>

    </div>

@endsection


