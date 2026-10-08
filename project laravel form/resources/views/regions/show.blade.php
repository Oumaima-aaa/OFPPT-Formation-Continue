@extends('layouts.app')



@section('title', $region->nom_region)



@php

    $header = $region->nom_region;

    $headerActions = '<a href="' . route('regions.edit', $region) . '" class="btn btn-primary">Modifier</a>';

@endphp



@section('content')

    <div class="row">

        <div class="col-lg-6">

            <div class="card mb-4">

                <div class="card-header">Informations</div>

                <div class="card-body">

                    <dl class="row mb-0">

                        <dt class="col-sm-4">Nom</dt>

                        <dd class="col-sm-8">{{ $region->nom_region }}</dd>

                        <dt class="col-sm-4">Administrateur</dt>

                        <dd class="col-sm-8">{{ $region->user->name ?? '—' }}</dd>

                        <dt class="col-sm-4">Créée le</dt>

                        <dd class="col-sm-8">{{ $region->created_at->format('d/m/Y H:i') }}</dd>

                    </dl>

                </div>

            </div>

        </div>

        <div class="col-lg-6">

            <div class="card mb-4">

                <div class="card-header">Établissements ({{ $region->etablissements->count() }})</div>

                <div class="card-body">

                    @if($region->etablissements->isNotEmpty())

                        <ul class="list-group list-group-flush">

                            @foreach($region->etablissements as $etablissement)

                                <li class="list-group-item d-flex justify-content-between align-items-center">

                                    {{ $etablissement->nom_efp }}

                                    <a href="{{ route('etablissements.show', $etablissement) }}" class="btn btn-sm btn-outline-info">Voir</a>

                                </li>

                            @endforeach

                        </ul>

                    @else

                        <p class="text-muted mb-0">Aucun établissement associé.</p>

                    @endif

                </div>

            </div>

        </div>

    </div>



    <div class="d-flex gap-2">

        <a href="{{ route('regions.index') }}" class="btn btn-secondary">Retour à la liste</a>

        <form action="{{ route('regions.destroy', $region) }}" method="POST" onsubmit="return confirm('Confirmer la suppression ?');">

            @csrf

            @method('DELETE')

            <button type="submit" class="btn btn-danger">Supprimer</button>

        </form>

    </div>

@endsection


