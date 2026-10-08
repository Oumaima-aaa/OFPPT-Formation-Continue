@extends('layouts.app')

@section('title', 'Intervenants')

@php
    use App\Models\Intervenant;
    $header = 'Intervenants';
    $headerActions = auth()->user()->can('create', Intervenant::class)
        ? '<a href="' . route('intervenants.create') . '" class="btn btn-primary">Nouvel intervenant</a>'
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
                            <th>Matricule</th>
                            <th>Nom complet</th>
                            <th>Email</th>
                            <th>Type</th>
                            <th>Compétences</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($intervenants as $intervenant)
                            <tr>
                                <td>{{ $intervenant->id }}</td>
                                <td>{{ $intervenant->matricule }}</td>
                                <td>{{ $intervenant->prenom }} {{ $intervenant->nom }}</td>
                                <td>{{ $intervenant->email }}</td>
                                <td>{{ $intervenant->type_intervenant === 'interne' ? 'Interne' : 'Externe' }}</td>
                                <td>{{ $intervenant->competences_count }}</td>
                                <td class="text-end">
                                    <a href="{{ route('intervenants.show', $intervenant) }}" class="btn btn-sm btn-outline-info">Voir</a>
                                    <a href="{{ route('intervenants.edit', $intervenant) }}" class="btn btn-sm btn-outline-primary">Modifier</a>
                                    <form action="{{ route('intervenants.destroy', $intervenant) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">Aucun intervenant trouvé.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
