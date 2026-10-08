@extends('layouts.app')

@section('title', $competence->nom)

@php
    $header = $competence->nom;
    $headerActions = '<a href="' . route('competences.edit', $competence) . '" class="btn btn-primary">Modifier</a>';
    $niveauLabels = ['debutant' => 'Débutant', 'intermediaire' => 'Intermédiaire', 'expert' => 'Expert'];
@endphp

@section('content')
    <div class="card mb-4">
        <div class="card-header">Détails</div>
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-4">Intervenant</dt>
                <dd class="col-sm-8">{{ $competence->intervenant->full_name ?? '—' }}</dd>
                <dt class="col-sm-4">Nom</dt>
                <dd class="col-sm-8">{{ $competence->nom }}</dd>
                <dt class="col-sm-4">Niveau</dt>
                <dd class="col-sm-8">{{ $niveauLabels[$competence->niveau] ?? $competence->niveau }}</dd>
                <dt class="col-sm-4">Description</dt>
                <dd class="col-sm-8">{{ $competence->description ?? '—' }}</dd>
            </dl>
        </div>
    </div>

    <div class="d-flex gap-2">
        <a href="{{ route('competences.index') }}" class="btn btn-secondary">Retour à la liste</a>
        <form action="{{ route('competences.destroy', $competence) }}" method="POST" onsubmit="return confirm('Confirmer la suppression ?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Supprimer</button>
        </form>
    </div>
@endsection
