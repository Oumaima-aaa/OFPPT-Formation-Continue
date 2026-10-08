@extends('layouts.app')

@section('title', $theme->intitule_theme)

@php
    $header = $theme->intitule_theme;
    $subtitle = $theme->domaine->nom_domaine ?? 'Catalogue formations';
    $headerActions = '<a href="' . route('catalog.index') . '" class="btn btn-outline-secondary">Retour au catalogue</a>';
@endphp

@section('content')
    <div class="card mb-4">
        <div class="card-header">Détails du thème</div>
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-4">Domaine</dt>
                <dd class="col-sm-8">
                    <a href="{{ route('catalog.domaines.show', $theme->domaine) }}">{{ $theme->domaine->nom_domaine ?? '—' }}</a>
                </dd>
                <dt class="col-sm-4">Intitulé</dt>
                <dd class="col-sm-8">{{ $theme->intitule_theme }}</dd>
                <dt class="col-sm-4">Durée</dt>
                <dd class="col-sm-8">{{ $theme->duree_formation }} jours</dd>
                <dt class="col-sm-4">Participants max / groupe</dt>
                <dd class="col-sm-8">{{ $theme->nbparticipantmaxi ?? '—' }}</dd>
            </dl>
        </div>
    </div>

    @can('create', App\Models\Action::class)
        <a href="{{ route('actions.create', ['themes_id' => $theme->id]) }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Demander une formation
        </a>
    @endcan
@endsection
