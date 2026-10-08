@extends('layouts.app')



@section('title', $entreprise->raison)



@php

    $header = $entreprise->raison;

    $headerActions = '<a href="' . route('entreprises.edit', $entreprise) . '" class="btn btn-primary">Modifier</a>';

@endphp



@section('content')

    <div class="row">

        <div class="col-lg-6">

            <div class="card mb-4">

                <div class="card-header">Informations</div>

                <div class="card-body">

                    @if($entreprise->logo)

                        <div class="mb-3 text-center">

                            <img src="{{ Storage::url($entreprise->logo) }}" alt="Logo" class="img-thumbnail" style="max-height: 120px;">

                        </div>

                    @endif

                    <dl class="row mb-0">

                        <dt class="col-sm-5">Raison sociale</dt>

                        <dd class="col-sm-7">{{ $entreprise->raison }}</dd>

                        <dt class="col-sm-5">Email</dt>

                        <dd class="col-sm-7">{{ $entreprise->email }}</dd>

                        <dt class="col-sm-5">Site web</dt>

                        <dd class="col-sm-7">{{ $entreprise->site ?? '—' }}</dd>

                        <dt class="col-sm-5">Téléphone 1</dt>

                        <dd class="col-sm-7">{{ $entreprise->telephone1 }}</dd>

                        <dt class="col-sm-5">Téléphone 2</dt>

                        <dd class="col-sm-7">{{ $entreprise->telephone2 ?? '—' }}</dd>

                        <dt class="col-sm-5">Téléphone 3</dt>

                        <dd class="col-sm-7">{{ $entreprise->telephone3 ?? '—' }}</dd>

                        <dt class="col-sm-5">Représentant</dt>

                        <dd class="col-sm-7">{{ $entreprise->representant }}</dd>

                        <dt class="col-sm-5">Utilisateur</dt>

                        <dd class="col-sm-7">{{ $entreprise->user->name ?? '—' }}</dd>

                        <dt class="col-sm-5">Statut</dt>

                        <dd class="col-sm-7">

                            <span class="badge bg-{{ $entreprise->isActive() ? 'success' : 'secondary' }}">

                                {{ $entreprise->status_label }}

                            </span>

                        </dd>

                    </dl>

                </div>

            </div>

        </div>

        <div class="col-lg-6">

            <div class="card mb-4">

                <div class="card-header">Actions ({{ $entreprise->actions->count() }})</div>

                <div class="card-body">

                    @if($entreprise->actions->isNotEmpty())

                        <ul class="list-group list-group-flush">

                            @foreach($entreprise->actions->take(5) as $action)

                                <li class="list-group-item d-flex justify-content-between align-items-center">

                                    {{ $action->theme->intitule_theme ?? '—' }}

                                    <a href="{{ route('actions.show', $action) }}" class="btn btn-sm btn-outline-info">Voir</a>

                                </li>

                            @endforeach

                        </ul>

                    @else

                        <p class="text-muted mb-0">Aucune action associée.</p>

                    @endif

                </div>

            </div>

        </div>

    </div>



    <div class="d-flex gap-2">

        <a href="{{ route('entreprises.index') }}" class="btn btn-secondary">Retour à la liste</a>

        <form action="{{ route('entreprises.destroy', $entreprise) }}" method="POST" onsubmit="return confirm('Confirmer la suppression ?');">

            @csrf

            @method('DELETE')

            <button type="submit" class="btn btn-danger">Supprimer</button>

        </form>

    </div>

@endsection


