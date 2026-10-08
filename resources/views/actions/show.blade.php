@extends('layouts.app')

@section('title', 'Action #' . $action->id)

@php
    use App\Models\Action;
    $header = (auth()->user()->isCompany() ? 'Demande' : 'Action') . ' de formation #' . $action->id;
    $headerActions = auth()->user()->can('update', $action)
        ? '<a href="' . route('actions.edit', $action) . '" class="btn btn-primary">Modifier</a>'
        : null;
@endphp

@section('content')
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card mb-4">
        <div class="card-header">Détails {{ auth()->user()->isCompany() ? 'de la demande' : 'de l\'action' }}</div>
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-4">Exercice</dt>
                <dd class="col-sm-8">{{ $action->exercice }}</dd>
                <dt class="col-sm-4">Thème</dt>
                <dd class="col-sm-8">{{ $action->theme->intitule_theme ?? '—' }}</dd>
                <dt class="col-sm-4">Domaine</dt>
                <dd class="col-sm-8">{{ $action->theme->domaine->nom_domaine ?? '—' }}</dd>
                @unless(auth()->user()->isCompany())
                    <dt class="col-sm-4">Entreprise</dt>
                    <dd class="col-sm-8">{{ $action->entreprise->raison ?? '—' }}</dd>
                @endunless
                <dt class="col-sm-4">Établissement</dt>
                <dd class="col-sm-8">{{ $action->etablissement->nom_efp ?? '—' }} ({{ $action->etablissement->region->nom_region ?? '' }})</dd>
                <dt class="col-sm-4">Date de début</dt>
                <dd class="col-sm-8">{{ $action->date_debut->format('d/m/Y') }}</dd>
                <dt class="col-sm-4">Date de fin</dt>
                <dd class="col-sm-8">{{ $action->date_fin->format('d/m/Y') }}</dd>
                <dt class="col-sm-4">Prix réel</dt>
                <dd class="col-sm-8">{{ number_format($action->prix_reel, 2, ',', ' ') }} DH</dd>
                <dt class="col-sm-4">Statut</dt>
                <dd class="col-sm-8"><span class="badge bg-secondary">{{ $action->status_label }}</span></dd>
            </dl>
        </div>
    </div>

    @if($action->intervenants->isNotEmpty())
        <div class="card mb-4">
            <div class="card-header">Intervenants</div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Statut</th>
                            @can('assignIntervenant', $action)
                                <th class="text-end">Actions</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($action->intervenants as $intervenant)
                            <tr>
                                <td>{{ $intervenant->full_name }}</td>
                                <td>{{ $intervenant->email }}</td>
                                <td>
                                    <span class="badge bg-{{ $intervenant->pivot->status == Action::INTERVENANT_ASSIGNED ? 'success' : ($intervenant->pivot->status == Action::INTERVENANT_POSTULATED ? 'warning' : 'secondary') }}">
                                        {{ Action::intervenantStatusLabels()[$intervenant->pivot->status] ?? '—' }}
                                    </span>
                                </td>
                                @can('assignIntervenant', $action)
                                    <td class="text-end">
                                        @if((int) $intervenant->pivot->status === Action::INTERVENANT_POSTULATED)
                                            <form action="{{ route('actions.assign', $action) }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="intervenant_id" value="{{ $intervenant->id }}">
                                                <button type="submit" class="btn btn-sm btn-success">Affecter</button>
                                            </form>
                                            <form action="{{ route('actions.intervenants.reject', [$action, $intervenant]) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Refuser</button>
                                            </form>
                                        @endif
                                    </td>
                                @endcan
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    @can('assignIntervenant', $action)
        <div class="card mb-4">
            <div class="card-header">Affecter un intervenant</div>
            <div class="card-body">
                <form action="{{ route('actions.assign', $action) }}" method="POST" class="row g-2 align-items-end">
                    @csrf
                    <div class="col-md-8">
                        <label for="intervenant_id" class="form-label">Intervenant</label>
                        <select name="intervenant_id" id="intervenant_id" class="form-select" required>
                            <option value="">— Sélectionner —</option>
                            @foreach($availableIntervenants as $intervenant)
                                <option value="{{ $intervenant->id }}">{{ $intervenant->full_name }} ({{ $intervenant->matricule }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary w-100">Affecter</button>
                    </div>
                </form>
            </div>
        </div>
    @endcan

    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('actions.index') }}" class="btn btn-secondary">Retour à la liste</a>
        @can('approve', $action)
            <form action="{{ route('actions.approve', $action) }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-success">Valider la demande</button>
            </form>
        @endcan
        @can('reject', $action)
            <form action="{{ route('actions.reject', $action) }}" method="POST" onsubmit="return confirm('Refuser cette demande ?');">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-outline-danger">Refuser</button>
            </form>
        @endcan
        @can('start', $action)
            <form action="{{ route('actions.start', $action) }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-success">Démarrer</button>
            </form>
        @endcan
        @can('finish', $action)
            <form action="{{ route('actions.finish', $action) }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-primary">Terminer</button>
            </form>
        @endcan
        @can('cancel', $action)
            <form action="{{ route('actions.cancel', $action) }}" method="POST" onsubmit="return confirm('Confirmer l\'annulation ?');">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-warning">Annuler</button>
            </form>
        @endcan
        @can('delete', $action)
            @unless(auth()->user()->isCompany())
                <form action="{{ route('actions.destroy', $action) }}" method="POST" onsubmit="return confirm('Confirmer la suppression ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            @endunless
        @endcan
    </div>
@endsection
