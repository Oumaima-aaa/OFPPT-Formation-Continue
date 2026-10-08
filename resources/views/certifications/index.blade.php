@extends('layouts.app')

@section('title', 'Certifications')

@php
    $header = 'Certifications';
    $headerActions = '<a href="' . route('certifications.create') . '" class="btn btn-primary">Nouvelle certification</a>';
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
                            <th>Code</th>
                            <th>Intitulé</th>
                            <th>Type</th>
                            <th>Domaine</th>
                            <th>Organisme</th>
                            <th>Date obtention</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($certifications as $certification)
                            <tr>
                                <td>{{ $certification->id }}</td>
                                <td>{{ $certification->intervenant->full_name ?? '—' }}</td>
                                <td>{{ $certification->code ?? '—' }}</td>
                                <td>{{ $certification->intitule }}</td>
                                <td>{{ $certification->type ?? '—' }}</td>
                                <td>{{ $certification->domaine ?? '—' }}</td>
                                <td>{{ $certification->organisme ?? '—' }}</td>
                                <td>{{ $certification->date_obtention?->format('d/m/Y') ?? '—' }}</td>
                                <td class="text-end">
                                    <a href="{{ route('certifications.show', $certification) }}" class="btn btn-sm btn-outline-info">Voir</a>
                                    <a href="{{ route('certifications.edit', $certification) }}" class="btn btn-sm btn-outline-primary">Modifier</a>
                                    <form action="{{ route('certifications.destroy', $certification) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">Aucune certification trouvée.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
