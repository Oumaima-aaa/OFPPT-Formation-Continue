@extends('layouts.app')

@section('title', auth()->user()->isTrainer() ? 'Mon profil intervenant' : 'Modifier l\'intervenant')

@php
    $header = auth()->user()->isTrainer() ? 'Mon profil intervenant' : 'Modifier l\'intervenant';
@endphp

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('intervenants.update', $intervenant) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    @unless(auth()->user()->isTrainer())
                    <div class="col-md-4 mb-3">
                        <label for="matricule" class="form-label">Matricule <span class="text-danger">*</span></label>
                        <input type="text" name="matricule" id="matricule" class="form-control @error('matricule') is-invalid @enderror" value="{{ old('matricule', $intervenant->matricule) }}" required>
                        @error('matricule')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    @endunless
                    <div class="col-md-4 mb-3">
                        <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                        <input type="text" name="nom" id="nom" class="form-control @error('nom') is-invalid @enderror" value="{{ old('nom', $intervenant->nom) }}" required>
                        @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="prenom" class="form-label">Prénom <span class="text-danger">*</span></label>
                        <input type="text" name="prenom" id="prenom" class="form-control @error('prenom') is-invalid @enderror" value="{{ old('prenom', $intervenant->prenom) }}" required>
                        @error('prenom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $intervenant->email) }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="telephone" class="form-label">Téléphone</label>
                        <input type="text" name="telephone" id="telephone" class="form-control @error('telephone') is-invalid @enderror" value="{{ old('telephone', $intervenant->telephone) }}">
                        @error('telephone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="adresse" class="form-label">Adresse</label>
                    <textarea name="adresse" id="adresse" rows="2" class="form-control @error('adresse') is-invalid @enderror">{{ old('adresse', $intervenant->adresse) }}</textarea>
                    @error('adresse')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="date_naissance" class="form-label">Date de naissance</label>
                        <input type="date" name="date_naissance" id="date_naissance" class="form-control @error('date_naissance') is-invalid @enderror" value="{{ old('date_naissance', $intervenant->date_naissance?->format('Y-m-d')) }}">
                        @error('date_naissance')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="genre" class="form-label">Genre <span class="text-danger">*</span></label>
                        <select name="genre" id="genre" class="form-select @error('genre') is-invalid @enderror" required>
                            <option value="M" @selected(old('genre', $intervenant->genre) === 'M')>Masculin</option>
                            <option value="F" @selected(old('genre', $intervenant->genre) === 'F')>Féminin</option>
                        </select>
                        @error('genre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    @unless(auth()->user()->isTrainer())
                    <div class="col-md-4 mb-3">
                        <label for="type_intervenant" class="form-label">Type <span class="text-danger">*</span></label>
                        <select name="type_intervenant" id="type_intervenant" class="form-select @error('type_intervenant') is-invalid @enderror" required>
                            <option value="interne" @selected(old('type_intervenant', $intervenant->type_intervenant) === 'interne')>Interne</option>
                            <option value="externe" @selected(old('type_intervenant', $intervenant->type_intervenant) === 'externe')>Externe</option>
                        </select>
                        @error('type_intervenant')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    @endunless
                </div>

                @unless(auth()->user()->isTrainer())
                <div class="mb-3">
                    <label for="etablissements_id" class="form-label">Établissement <span class="text-danger">*</span></label>
                    <select name="etablissements_id" id="etablissements_id" class="form-select @error('etablissements_id') is-invalid @enderror" required>
                        @foreach($etablissements as $etablissement)
                            <option value="{{ $etablissement->id }}" @selected(old('etablissements_id', $intervenant->etablissements_id) == $etablissement->id)>{{ $etablissement->nom_efp }}</option>
                        @endforeach
                    </select>
                    @error('etablissements_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                @else
                <div class="mb-3">
                    <label class="form-label">Établissement</label>
                    <p class="form-control-plaintext mb-0">{{ $intervenant->etablissement->nom_efp ?? '—' }}</p>
                </div>
                @endunless

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                    @unless(auth()->user()->isTrainer())
                    <a href="{{ route('intervenants.index') }}" class="btn btn-secondary">Annuler</a>
                    @else
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">Annuler</a>
                    @endunless
                </div>
            </form>
        </div>
    </div>
@endsection
