@extends('layouts.app')

@section('title', 'Compétences')

@php
    $header = 'Compétences';
    $headerActions = '<a href="' . route('competences.create') . '" class="btn btn-primary">Nouvelle compétence</a>';
    $niveauLabels = ['debutant' => 'Débutant', 'intermediaire' => 'Intermédiaire', 'expert' => 'Expert'];
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
                            <th>Nom</th>
                            <th>Niveau</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($competences as $competence)
                            <tr>
                                <td>{{ $competence->id }}</td>
                                <td>{{ $competence->intervenant->full_name ?? '—' }}</td>
                                <td>{{ $competence->nom }}</td>
                                <td>{{ $niveauLabels[$competence->niveau] ?? $competence->niveau }}</td>
                                <td class="text-end">
                                    <a href="{{ route('competences.show', $competence) }}" class="btn btn-sm btn-outline-info">Voir</a>
                                    <a href="{{ route('competences.edit', $competence) }}" class="btn btn-sm btn-outline-primary">Modifier</a>
                                    <form action="{{ route('competences.destroy', $competence) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Aucune compétence trouvée.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
