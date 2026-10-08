@extends('layouts.app')

@section('title', 'Plan #' . $plan->id)

@php
    $header = 'Plan de formation #' . $plan->id;
    $headerActions = auth()->user()->can('update', $plan)
        ? '<a href="' . route('plans.edit', $plan) . '" class="btn btn-primary">Modifier</a>'
        : null;
@endphp

@section('content')
    <div class="card mb-4">
        <div class="card-header">Détails du plan</div>
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-4">Exercice</dt>
                <dd class="col-sm-8">{{ $plan->exercice }}</dd>
                <dt class="col-sm-4">Établissement</dt>
                <dd class="col-sm-8">{{ $plan->etablissement->nom_efp ?? '—' }} ({{ $plan->etablissement->region->nom_region ?? '' }})</dd>
                <dt class="col-sm-4">Thème</dt>
                <dd class="col-sm-8">{{ $plan->theme->intitule_theme ?? '—' }}</dd>
                <dt class="col-sm-4">Domaine</dt>
                <dd class="col-sm-8">{{ $plan->theme->domaine->nom_domaine ?? '—' }}</dd>
                <dt class="col-sm-4">Nombre de jours</dt>
                <dd class="col-sm-8">{{ $plan->nbjours }}</dd>
                <dt class="col-sm-4">Participants max</dt>
                <dd class="col-sm-8">{{ $plan->nbparticipantmaxi }}</dd>
                <dt class="col-sm-4">Nombre de groupes</dt>
                <dd class="col-sm-8">{{ $plan->nb_groupes ?? 1 }}</dd>
                <dt class="col-sm-4">Début prévisionnel</dt>
                <dd class="col-sm-8">{{ $plan->date_debut_previsionnelle?->format('d/m/Y') ?? '—' }}</dd>
                <dt class="col-sm-4">Coût prévisionnel</dt>
                <dd class="col-sm-8">{{ number_format($plan->cout_previsionnel, 2, ',', ' ') }} DH</dd>
                <dt class="col-sm-4">Statut</dt>
                <dd class="col-sm-8"><span class="badge bg-secondary">{{ $plan->status_label }}</span></dd>
            </dl>
        </div>
    </div>

    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('plans.index') }}" class="btn btn-secondary">Retour à la liste</a>
        @can('approve', $plan)
            <form action="{{ route('plans.approve', $plan) }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-success">Valider le plan</button>
            </form>
        @endcan
        @can('reject', $plan)
            <form action="{{ route('plans.reject', $plan) }}" method="POST" onsubmit="return confirm('Refuser ce plan ?');">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-outline-danger">Refuser</button>
            </form>
        @endcan
        @can('cancel', $plan)
            <form action="{{ route('plans.cancel', $plan) }}" method="POST" onsubmit="return confirm('Confirmer l\'annulation ?');">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-warning">Annuler le plan</button>
            </form>
        @endcan
        @can('delete', $plan)
            <form action="{{ route('plans.destroy', $plan) }}" method="POST" onsubmit="return confirm('Confirmer la suppression ?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Supprimer</button>
            </form>
        @endcan
    </div>
@endsection
