@extends('layouts.app')

@section('title', $user->full_name)

@php
    $header = $user->full_name;
    $subtitle = $user->email;
    $headerActions = auth()->user()->can('update', $user)
        ? '<a href="' . route('users.edit', $user) . '" class="btn btn-primary btn-icon"><i class="bi bi-pencil"></i> Modifier</a>'
        : '';
@endphp

@section('content')
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header"><i class="bi bi-person me-2"></i>Informations</div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Prénom</dt>
                        <dd class="col-sm-8">{{ $user->first_name }}</dd>
                        <dt class="col-sm-4">Nom</dt>
                        <dd class="col-sm-8">{{ $user->last_name }}</dd>
                        <dt class="col-sm-4">E-mail</dt>
                        <dd class="col-sm-8">{{ $user->email }}</dd>
                        <dt class="col-sm-4">Téléphone</dt>
                        <dd class="col-sm-8">{{ $user->phone ?? '—' }}</dd>
                        <dt class="col-sm-4">Adresse</dt>
                        <dd class="col-sm-8">{{ $user->address ?? '—' }}</dd>
                        <dt class="col-sm-4">Rôle</dt>
                        <dd class="col-sm-8"><span class="badge bg-primary-subtle text-primary-emphasis">{{ $user->assignedRole?->name ?? '—' }}</span></dd>
                        <dt class="col-sm-4">Statut</dt>
                        <dd class="col-sm-8">
                            @if($user->isActive())
                                <span class="badge bg-success">Actif</span>
                            @else
                                <span class="badge bg-secondary">Inactif</span>
                            @endif
                        </dd>
                        <dt class="col-sm-4">Créé le</dt>
                        <dd class="col-sm-8">{{ $user->created_at?->format('d/m/Y H:i') }}</dd>
                        <dt class="col-sm-4">Modifié le</dt>
                        <dd class="col-sm-8">{{ $user->updated_at?->format('d/m/Y H:i') }}</dd>
                    </dl>
                </div>
            </div>

            @if($user->assignedRegion || $user->assignedEstablishment || $user->entreprise)
                <div class="card mt-4">
                    <div class="card-header"><i class="bi bi-link-45deg me-2"></i>Affectations</div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            @if($user->assignedRegion)
                                <li><i class="bi bi-geo-alt me-1"></i> Région : <strong>{{ $user->assignedRegion->nom_region }}</strong></li>
                            @endif
                            @if($user->assignedEstablishment)
                                <li><i class="bi bi-building me-1"></i> Établissement : <strong>{{ $user->assignedEstablishment->nom_efp }}</strong></li>
                            @endif
                            @if($user->entreprise)
                                <li><i class="bi bi-briefcase me-1"></i> Entreprise : <strong>{{ $user->entreprise->raison }}</strong></li>
                            @endif
                        </ul>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header"><i class="bi bi-gear me-2"></i>Actions</div>
                <div class="card-body d-grid gap-2">
                    @can('activate', $user)
                        @if(!$user->isActive())
                            <form method="POST" action="{{ route('users.activate', $user) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success w-100 btn-icon"><i class="bi bi-check-circle"></i> Activer le compte</button>
                            </form>
                        @endif
                    @endcan

                    @can('deactivate', $user)
                        @if($user->isActive())
                            <form method="POST" action="{{ route('users.deactivate', $user) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-warning w-100 btn-icon" onclick="return confirm('Désactiver ce compte ?');"><i class="bi bi-slash-circle"></i> Désactiver le compte</button>
                            </form>
                        @endif
                    @endcan

                    @can('resetPassword', $user)
                        <button type="button" class="btn btn-outline-primary w-100 btn-icon" data-bs-toggle="collapse" data-bs-target="#resetPasswordForm">
                            <i class="bi bi-key"></i> Réinitialiser le mot de passe
                        </button>
                        <div class="collapse mt-2" id="resetPasswordForm">
                            <form method="POST" action="{{ route('users.reset-password', $user) }}">
                                @csrf
                                @method('PATCH')
                                <div class="mb-2">
                                    <input type="password" name="password" class="form-control form-control-sm" placeholder="Nouveau mot de passe" required>
                                </div>
                                <div class="mb-2">
                                    <input type="password" name="password_confirmation" class="form-control form-control-sm" placeholder="Confirmation" required>
                                </div>
                                <button type="submit" class="btn btn-sm btn-primary w-100">Confirmer</button>
                            </form>
                        </div>
                    @endcan

                    @can('delete', $user)
                        <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('Supprimer définitivement cet utilisateur ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100 btn-icon"><i class="bi bi-trash"></i> Supprimer</button>
                        </form>
                    @endcan

                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary w-100">Retour à la liste</a>
                </div>
            </div>
        </div>
    </div>
@endsection
