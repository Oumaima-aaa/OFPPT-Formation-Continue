@extends('layouts.app')



@section('title', $domaine->nom_domaine)



@php

    $header = $domaine->nom_domaine;

    $headerActions = '<a href="' . route('domaines.edit', $domaine) . '" class="btn btn-primary">Modifier</a>';

@endphp



@section('content')

    <div class="row">

        <div class="col-lg-6">

            <div class="card mb-4">

                <div class="card-header">Informations</div>

                <div class="card-body">

                    <dl class="row mb-0">

                        <dt class="col-sm-4">Nom</dt>

                        <dd class="col-sm-8">{{ $domaine->nom_domaine }}</dd>

                        <dt class="col-sm-4">Statut</dt>

                        <dd class="col-sm-8">

                            <span class="badge bg-{{ $domaine->isActive() ? 'success' : 'secondary' }}">

                                {{ $domaine->status_label }}

                            </span>

                        </dd>

                    </dl>

                </div>

            </div>

        </div>

        <div class="col-lg-6">

            <div class="card mb-4">

                <div class="card-header">Thèmes ({{ $domaine->themes->count() }})</div>

                <div class="card-body">

                    @if($domaine->themes->isNotEmpty())

                        <ul class="list-group list-group-flush">

                            @foreach($domaine->themes as $theme)

                                <li class="list-group-item d-flex justify-content-between align-items-center">

                                    {{ $theme->intitule_theme }}

                                    <a href="{{ route('themes.show', $theme) }}" class="btn btn-sm btn-outline-info">Voir</a>

                                </li>

                            @endforeach

                        </ul>

                    @else

                        <p class="text-muted mb-0">Aucun thème associé.</p>

                    @endif

                </div>

            </div>

        </div>

    </div>



    <div class="d-flex gap-2">

        <a href="{{ route('domaines.index') }}" class="btn btn-secondary">Retour à la liste</a>

        <form action="{{ route('domaines.destroy', $domaine) }}" method="POST" onsubmit="return confirm('Confirmer la suppression ?');">

            @csrf

            @method('DELETE')

            <button type="submit" class="btn btn-danger">Supprimer</button>

        </form>

    </div>

@endsection


