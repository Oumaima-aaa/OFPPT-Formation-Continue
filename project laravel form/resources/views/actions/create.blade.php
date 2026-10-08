@extends('layouts.app')

@section('title', auth()->user()->isCompany() ? 'Nouvelle demande de formation' : 'Nouvelle action de formation')

@php
    $header = auth()->user()->isCompany() ? 'Nouvelle demande de formation' : 'Nouvelle action de formation';
    use App\Models\Action;
@endphp

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('actions.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="exercice" class="form-label">Exercice (année) <span class="text-danger">*</span></label>
                        <input type="number" name="exercice" id="exercice" class="form-control @error('exercice') is-invalid @enderror" value="{{ old('exercice', date('Y')) }}" min="2000" max="2100" required>
                        @error('exercice')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="themes_id" class="form-label">Thème <span class="text-danger">*</span></label>
                        <select name="themes_id" id="themes_id" class="form-select @error('themes_id') is-invalid @enderror" required>
                            <option value="">— Sélectionner —</option>
                            @foreach($themes as $theme)
                                <option value="{{ $theme->id }}" @selected(old('themes_id', request('themes_id')) == $theme->id)>{{ $theme->intitule_theme }}</option>
                            @endforeach
                        </select>
                        @error('themes_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    @unless(auth()->user()->isCompany())
                        <div class="col-md-3 mb-3">
                            <label for="entreprises_id" class="form-label">Entreprise <span class="text-danger">*</span></label>
                            <select name="entreprises_id" id="entreprises_id" class="form-select @error('entreprises_id') is-invalid @enderror" required>
                                <option value="">— Sélectionner —</option>
                                @foreach($entreprises as $entreprise)
                                    <option value="{{ $entreprise->id }}" @selected(old('entreprises_id') == $entreprise->id)>{{ $entreprise->raison }}</option>
                                @endforeach
                            </select>
                            @error('entreprises_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    @endunless
                    <div class="col-md-3 mb-3">
                        <label for="etablissements_id" class="form-label">Établissement <span class="text-danger">*</span></label>
                        <select name="etablissements_id" id="etablissements_id" class="form-select @error('etablissements_id') is-invalid @enderror" required>
                            <option value="">— Sélectionner —</option>
                            @foreach($etablissements as $etablissement)
                                <option value="{{ $etablissement->id }}" @selected(old('etablissements_id') == $etablissement->id)>{{ $etablissement->nom_efp }}</option>
                            @endforeach
                        </select>
                        @error('etablissements_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="date_debut" class="form-label">Date de début <span class="text-danger">*</span></label>
                        <input type="date" name="date_debut" id="date_debut" class="form-control @error('date_debut') is-invalid @enderror" value="{{ old('date_debut') }}" required>
                        @error('date_debut')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="date_fin" class="form-label">Date de fin <span class="text-danger">*</span></label>
                        <input type="date" name="date_fin" id="date_fin" class="form-control @error('date_fin') is-invalid @enderror" value="{{ old('date_fin') }}" required>
                        @error('date_fin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="prix_reel" class="form-label">Prix réel (DH) <span class="text-danger">*</span></label>
                        <input type="number" name="prix_reel" id="prix_reel" class="form-control @error('prix_reel') is-invalid @enderror" value="{{ old('prix_reel') }}" min="0" step="0.01" required>
                        @error('prix_reel')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    @if(auth()->user()->isRegionalManager() || auth()->user()->isLocalManager())
                        <div class="col-md-4 mb-3">
                            <label for="status" class="form-label">Statut <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                @foreach(Action::statusLabels() as $value => $label)
                                    <option value="{{ $value }}" @selected((int) old('status', Action::STATUS_APPROVED) === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    @endif
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">{{ auth()->user()->isCompany() ? 'Soumettre la demande' : 'Enregistrer' }}</button>
                    <a href="{{ route('actions.index') }}" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
@endsection
