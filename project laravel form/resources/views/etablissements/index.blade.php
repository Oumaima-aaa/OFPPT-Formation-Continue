@extends('layouts.app')



@section('title', 'Établissements')



@php

    use App\Models\Etablissement;

    $header = 'Établissements';

    $headerActions = auth()->user()->can('create', Etablissement::class)
        ? '<a href="' . route('etablissements.create') . '" class="btn btn-primary">Nouvel établissement</a>'
        : null;

@endphp



@section('content')

    @include('partials.search')



    <div class="card">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-striped table-hover align-middle">

                    <thead>

                        <tr>

                            <th>#</th>

                            <th>Nom EFP</th>

                            <th>Ville</th>

                            <th>Région</th>

                            <th>Statut</th>

                            <th class="text-end">Actions</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($etablissements as $etablissement)

                            <tr>

                                <td>{{ $etablissement->id }}</td>

                                <td>{{ $etablissement->nom_efp }}</td>

                                <td>{{ $etablissement->ville }}</td>

                                <td>{{ $etablissement->region->nom_region ?? '—' }}</td>

                                <td>

                                    <span class="badge bg-{{ $etablissement->isActive() ? 'success' : 'secondary' }}">

                                        {{ $etablissement->status_label }}

                                    </span>

                                </td>

                                <td class="text-end">

                                    <a href="{{ route('etablissements.show', $etablissement) }}" class="btn btn-sm btn-outline-info">Voir</a>
                                    @can('update', $etablissement)
                                    <a href="{{ route('etablissements.edit', $etablissement) }}" class="btn btn-sm btn-outline-primary">Modifier</a>
                                    @endcan
                                    @can('delete', $etablissement)
                                    <form action="{{ route('etablissements.destroy', $etablissement) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression ?');">

                                        @csrf

                                        @method('DELETE')

                                        <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>

                                    </form>
                                    @endcan

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6" class="text-center text-muted">Aucun établissement trouvé.</td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

@endsection


