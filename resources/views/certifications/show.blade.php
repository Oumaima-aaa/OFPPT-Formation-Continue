@extends('layouts.app')

@section('title', $certification->intitule)

@php
    $header = $certification->intitule;
    $headerActions = '<a href="' . route('certifications.edit', $certification) . '" class="btn btn-primary">Modifier</a>';
@endphp

@section('content')
    <div class="card mb-4">
        <div class="card-header">Détails</div>
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-4">Intervenant</dt>
                <dd class="col-sm-8">{{ $certification->intervenant->full_name ?? '—' }}</dd>
                <dt class="col-sm-4">Code</dt>
                <dd class="col-sm-8">{{ $certification->code ?? '—' }}</dd>
                <dt class="col-sm-4">Intitulé</dt>
                <dd class="col-sm-8">{{ $certification->intitule }}</dd>
                <dt class="col-sm-4">Type</dt>
                <dd class="col-sm-8">{{ $certification->type ?? '—' }}</dd>
                <dt class="col-sm-4">Domaine</dt>
                <dd class="col-sm-8">{{ $certification->domaine ?? '—' }}</dd>
                <dt class="col-sm-4">Organisme délivrant</dt>
                <dd class="col-sm-8">{{ $certification->organisme ?? '—' }}</dd>
                <dt class="col-sm-4">Date d'obtention</dt>
                <dd class="col-sm-8">{{ $certification->date_obtention?->format('d/m/Y') ?? '—' }}</dd>
            </dl>
        </div>
    </div>

    <div class="d-flex gap-2">
        <a href="{{ route('certifications.index') }}" class="btn btn-secondary">Retour à la liste</a>
        <form action="{{ route('certifications.destroy', $certification) }}" method="POST" onsubmit="return confirm('Confirmer la suppression ?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Supprimer</button>
        </form>
    </div>
@endsection
