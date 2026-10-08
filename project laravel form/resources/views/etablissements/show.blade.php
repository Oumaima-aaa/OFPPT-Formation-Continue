@extends('layouts.app')



@section('title', $etablissement->nom_efp)



@php

    $header = $etablissement->nom_efp;

    $headerActions = '<a href="' . route('etablissements.edit', $etablissement) . '" class="btn btn-primary">Modifier</a>';

@endphp



@section('content')

    <div class="row">

        <div class="col-lg-6">

            <div class="card mb-4">

                <div class="card-header">Informations</div>

                <div class="card-body">

                    <dl class="row mb-0">

                        <dt class="col-sm-4">Nom EFP</dt>

                        <dd class="col-sm-8">{{ $etablissement->nom_efp }}</dd>

                        <dt class="col-sm-4">Adresse</dt>

                        <dd class="col-sm-8">{{ $etablissement->adresse }}</dd>

                        <dt class="col-sm-4">Ville</dt>

                        <dd class="col-sm-8">{{ $etablissement->ville }}</dd>

                        <dt class="col-sm-4">Téléphone</dt>

                        <dd class="col-sm-8">{{ $etablissement->tel }}</dd>

                        <dt class="col-sm-4">Région</dt>

                        <dd class="col-sm-8">{{ $etablissement->region->nom_region ?? '—' }}</dd>

                        <dt class="col-sm-4">Administrateur</dt>

                        <dd class="col-sm-8">{{ $etablissement->user->name ?? '—' }}</dd>

                        <dt class="col-sm-4">Statut</dt>

                        <dd class="col-sm-8">

                            <span class="badge bg-{{ $etablissement->isActive() ? 'success' : 'secondary' }}">

                                {{ $etablissement->status_label }}

                            </span>

                        </dd>

                    </dl>

                </div>

            </div>

        </div>

        <div class="col-lg-6">

            <div class="card mb-4">

                <div class="card-header">Plans récents ({{ $etablissement->plans->count() }})</div>

                <div class="card-body">

                    @if($etablissement->plans->isNotEmpty())

                        <ul class="list-group list-group-flush">

                            @foreach($etablissement->plans->take(5) as $plan)

                                <li class="list-group-item d-flex justify-content-between align-items-center">

                                    {{ $plan->theme->intitule_theme ?? '—' }}

                                    <a href="{{ route('plans.show', $plan) }}" class="btn btn-sm btn-outline-info">Voir</a>

                                </li>

                            @endforeach

                        </ul>

                    @else

                        <p class="text-muted mb-0">Aucun plan associé.</p>

                    @endif

                </div>

            </div>

        </div>

    </div>



    <div class="d-flex gap-2">

        <a href="{{ route('etablissements.index') }}" class="btn btn-secondary">Retour à la liste</a>

        <form action="{{ route('etablissements.destroy', $etablissement) }}" method="POST" onsubmit="return confirm('Confirmer la suppression ?');">

            @csrf

            @method('DELETE')

            <button type="submit" class="btn btn-danger">Supprimer</button>

        </form>

    </div>

@endsection


