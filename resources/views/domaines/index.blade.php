@extends('layouts.app')



@section('title', 'Domaines')



@php

    use App\Models\Domaine;

    $header = 'Domaines';

    $headerActions = auth()->user()->can('create', Domaine::class)
        ? '<a href="' . route('domaines.create') . '" class="btn btn-primary">Nouveau domaine</a>'
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

                            <th>Nom du domaine</th>

                            <th>Statut</th>

                            <th class="text-end">Actions</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($domaines as $domaine)

                            <tr>

                                <td>{{ $domaine->id }}</td>

                                <td>{{ $domaine->nom_domaine }}</td>

                                <td>

                                    <span class="badge bg-{{ $domaine->isActive() ? 'success' : 'secondary' }}">

                                        {{ $domaine->status_label }}

                                    </span>

                                </td>

                                <td class="text-end">

                                    <a href="{{ route('domaines.show', $domaine) }}" class="btn btn-sm btn-outline-info">Voir</a>
                                    @can('update', $domaine)
                                    <a href="{{ route('domaines.edit', $domaine) }}" class="btn btn-sm btn-outline-primary">Modifier</a>
                                    @endcan
                                    @can('delete', $domaine)
                                    <form action="{{ route('domaines.destroy', $domaine) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression ?');">

                                        @csrf

                                        @method('DELETE')

                                        <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>

                                    </form>
                                    @endcan

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="4" class="text-center text-muted">Aucun domaine trouvé.</td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

@endsection


