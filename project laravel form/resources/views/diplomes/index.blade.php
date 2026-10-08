@extends('layouts.app')

@section('title', 'Diplômes')

@php
    $header = 'Diplômes';
    $headerActions = '<a href="' . route('diplomes.create') . '" class="btn btn-primary">Nouveau diplôme</a>';
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
                            <th>Intervenant</th>
                            <th>Intitulé</th>
                            <th>Université</th>
                            <th>Année</th>
                            <th>Niveau</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($diplomes as $diplome)
                            <tr>
                                <td>{{ $diplome->id }}</td>
                                <td>{{ $diplome->intervenant->full_name ?? '—' }}</td>
                                <td>{{ $diplome->intitule }}</td>
                                <td>{{ $diplome->universite }}</td>
                                <td>{{ $diplome->annee_obtention }}</td>
                                <td>{{ $diplome->niveau }}</td>
                                <td class="text-end">
                                    <a href="{{ route('diplomes.show', $diplome) }}" class="btn btn-sm btn-outline-info">Voir</a>
                                    <a href="{{ route('diplomes.edit', $diplome) }}" class="btn btn-sm btn-outline-primary">Modifier</a>
                                    <form action="{{ route('diplomes.destroy', $diplome) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">Aucun diplôme trouvé.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
