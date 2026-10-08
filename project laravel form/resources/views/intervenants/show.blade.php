@extends('layouts.app')

@section('title', $intervenant->full_name)

@php
    $header = $intervenant->full_name;
    $headerActions = '<a href="' . route('intervenants.edit', $intervenant) . '" class="btn btn-primary">Modifier</a>';
@endphp

@section('content')
    <div class="row">
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header">Informations</div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-5">Matricule</dt>
                        <dd class="col-sm-7">{{ $intervenant->matricule }}</dd>
                        <dt class="col-sm-5">Nom complet</dt>
                        <dd class="col-sm-7">{{ $intervenant->prenom }} {{ $intervenant->nom }}</dd>
                        <dt class="col-sm-5">Email</dt>
                        <dd class="col-sm-7">{{ $intervenant->email }}</dd>
                        <dt class="col-sm-5">Téléphone</dt>
                        <dd class="col-sm-7">{{ $intervenant->telephone ?? '—' }}</dd>
                        <dt class="col-sm-5">Adresse</dt>
                        <dd class="col-sm-7">{{ $intervenant->adresse ?? '—' }}</dd>
                        <dt class="col-sm-5">Date de naissance</dt>
                        <dd class="col-sm-7">{{ $intervenant->date_naissance?->format('d/m/Y') ?? '—' }}</dd>
                        <dt class="col-sm-5">Genre</dt>
                        <dd class="col-sm-7">{{ $intervenant->genre === 'M' ? 'Masculin' : 'Féminin' }}</dd>
                        <dt class="col-sm-5">Type</dt>
                        <dd class="col-sm-7">{{ $intervenant->type_intervenant === 'interne' ? 'Interne' : 'Externe' }}</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header">Compétences ({{ $intervenant->competences->count() }})</div>
                <div class="card-body">
                    @if($intervenant->competences->isNotEmpty())
                        <ul class="list-group list-group-flush">
                            @foreach($intervenant->competences as $competence)
                                <li class="list-group-item">{{ $competence->nom }} — {{ ucfirst($competence->niveau) }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted mb-0">Aucune compétence enregistrée.</p>
                    @endif
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header">Diplômes ({{ $intervenant->diplomes->count() }})</div>
                <div class="card-body">
                    @if($intervenant->diplomes->isNotEmpty())
                        <ul class="list-group list-group-flush">
                            @foreach($intervenant->diplomes as $diplome)
                                <li class="list-group-item">{{ $diplome->intitule }} ({{ $diplome->annee_obtention }})</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted mb-0">Aucun diplôme enregistré.</p>
                    @endif
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header">Certifications ({{ $intervenant->certifications->count() }})</div>
                <div class="card-body">
                    @if($intervenant->certifications->isNotEmpty())
                        <ul class="list-group list-group-flush">
                            @foreach($intervenant->certifications as $certification)
                                <li class="list-group-item">{{ $certification->intitule }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted mb-0">Aucune certification enregistrée.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2">
        <a href="{{ route('intervenants.index') }}" class="btn btn-secondary">Retour à la liste</a>
        <form action="{{ route('intervenants.destroy', $intervenant) }}" method="POST" onsubmit="return confirm('Confirmer la suppression ?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Supprimer</button>
        </form>
    </div>
@endsection
