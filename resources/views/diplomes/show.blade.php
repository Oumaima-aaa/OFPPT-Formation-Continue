@extends('layouts.app')

@section('title', $diplome->intitule)

@php
    $header = $diplome->intitule;
    $headerActions = '<a href="' . route('diplomes.edit', $diplome) . '" class="btn btn-primary">Modifier</a>';
@endphp

@section('content')
    <div class="card mb-4">
        <div class="card-header">Détails</div>
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-4">Intervenant</dt>
                <dd class="col-sm-8">{{ $diplome->intervenant->full_name ?? '—' }}</dd>
                <dt class="col-sm-4">Intitulé</dt>
                <dd class="col-sm-8">{{ $diplome->intitule }}</dd>
                <dt class="col-sm-4">Université</dt>
                <dd class="col-sm-8">{{ $diplome->universite }}</dd>
                <dt class="col-sm-4">Année d'obtention</dt>
                <dd class="col-sm-8">{{ $diplome->annee_obtention }}</dd>
                <dt class="col-sm-4">Niveau</dt>
                <dd class="col-sm-8">{{ $diplome->niveau }}</dd>
                <dt class="col-sm-4">Spécialité</dt>
                <dd class="col-sm-8">{{ $diplome->specialite ?? '—' }}</dd>
            </dl>
        </div>
    </div>

    <div class="d-flex gap-2">
        <a href="{{ route('diplomes.index') }}" class="btn btn-secondary">Retour à la liste</a>
        <form action="{{ route('diplomes.destroy', $diplome) }}" method="POST" onsubmit="return confirm('Confirmer la suppression ?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Supprimer</button>
        </form>
    </div>
@endsection
