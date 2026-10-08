@extends('layouts.app')



@section('title', 'Entreprises')



@php

    $header = 'Entreprises';

    $headerActions = '<a href="' . route('entreprises.create') . '" class="btn btn-primary">Nouvelle entreprise</a>';

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

                            <th>Logo</th>

                            <th>Raison sociale</th>

                            <th>Email</th>

                            <th>Représentant</th>

                            <th>Statut</th>

                            <th class="text-end">Actions</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($entreprises as $entreprise)

                            <tr>

                                <td>{{ $entreprise->id }}</td>

                                <td>

                                    @if($entreprise->logo)

                                        <img src="{{ Storage::url($entreprise->logo) }}" alt="Logo" class="rounded" style="height: 40px;">

                                    @else

                                        <span class="text-muted">—</span>

                                    @endif

                                </td>

                                <td>{{ $entreprise->raison }}</td>

                                <td>{{ $entreprise->email }}</td>

                                <td>{{ $entreprise->representant }}</td>

                                <td>

                                    <span class="badge bg-{{ $entreprise->isActive() ? 'success' : 'secondary' }}">

                                        {{ $entreprise->status_label }}

                                    </span>

                                </td>

                                <td class="text-end">

                                    <a href="{{ route('entreprises.show', $entreprise) }}" class="btn btn-sm btn-outline-info">Voir</a>

                                    <a href="{{ route('entreprises.edit', $entreprise) }}" class="btn btn-sm btn-outline-primary">Modifier</a>

                                    <form action="{{ route('entreprises.destroy', $entreprise) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression ?');">

                                        @csrf

                                        @method('DELETE')

                                        <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>

                                    </form>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7" class="text-center text-muted">Aucune entreprise trouvée.</td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

@endsection


