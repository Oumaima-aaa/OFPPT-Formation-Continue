@extends('layouts.app')

@section('title', auth()->user()->isCompany() ? 'Mes plans de formation' : 'Plans de formation')

@php
    $header = auth()->user()->isCompany() ? 'Mes plans de formation' : 'Plans de formation';
    $headerActions = auth()->user()->can('create', App\Models\Plan::class)
        ? '<a href="' . route('plans.create') . '" class="btn btn-primary">Nouveau plan</a>'
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
                            <th>Exercice</th>
                            <th>Établissement</th>
                            <th>Thème</th>
                            <th>Jours</th>
                            <th>Participants max</th>
                            <th>Coût prév.</th>
                            <th>Statut</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($plans as $plan)
                            <tr>
                                <td>{{ $plan->id }}</td>
                                <td>{{ $plan->exercice }}</td>
                                <td>{{ $plan->etablissement->nom_efp ?? '—' }}</td>
                                <td>{{ $plan->theme->intitule_theme ?? '—' }}</td>
                                <td>{{ $plan->nbjours }}</td>
                                <td>{{ $plan->nbparticipantmaxi }}</td>
                                <td>{{ number_format($plan->cout_previsionnel, 2, ',', ' ') }} DH</td>
                                <td><span class="badge bg-secondary">{{ $plan->status_label }}</span></td>
                                <td class="text-end">
                                    <a href="{{ route('plans.show', $plan) }}" class="btn btn-sm btn-outline-info">Voir</a>
                                    @can('update', $plan)
                                        <a href="{{ route('plans.edit', $plan) }}" class="btn btn-sm btn-outline-primary">Modifier</a>
                                    @endcan
                                    @can('cancel', $plan)
                                        <form action="{{ route('plans.cancel', $plan) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer l\'annulation ?');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-warning">Annuler</button>
                                        </form>
                                    @endcan
                                    @can('delete', $plan)
                                        <form action="{{ route('plans.destroy', $plan) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="9" class="text-center text-muted">Aucun plan trouvé.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
