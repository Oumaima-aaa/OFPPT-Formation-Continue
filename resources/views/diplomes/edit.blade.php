@extends('layouts.app')

@section('title', 'Modifier le diplôme')

@php
    $header = 'Modifier le diplôme';
@endphp

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('diplomes.update', $diplome) }}" method="POST">
                @csrf
                @method('PUT')

                @unless(auth()->user()->isTrainer())
                <div class="mb-3">
                    <label for="intervenant_id" class="form-label">Intervenant <span class="text-danger">*</span></label>
                    <select name="intervenant_id" id="intervenant_id" class="form-select @error('intervenant_id') is-invalid @enderror" required>
                        <option value="">— Sélectionner —</option>
                        @foreach($intervenants as $intervenant)
                            <option value="{{ $intervenant->id }}" @selected(old('intervenant_id', $diplome->intervenant_id) == $intervenant->id)>{{ $intervenant->prenom }} {{ $intervenant->nom }}</option>
                        @endforeach
                    </select>
                    @error('intervenant_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                @endunless

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="intitule" class="form-label">Intitulé <span class="text-danger">*</span></label>
                        <input type="text" name="intitule" id="intitule" class="form-control @error('intitule') is-invalid @enderror" value="{{ old('intitule', $diplome->intitule) }}" required>
                        @error('intitule')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="universite" class="form-label">Université <span class="text-danger">*</span></label>
                        <input type="text" name="universite" id="universite" class="form-control @error('universite') is-invalid @enderror" value="{{ old('universite', $diplome->universite) }}" required>
                        @error('universite')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="annee_obtention" class="form-label">Année d'obtention <span class="text-danger">*</span></label>
                        <input type="number" name="annee_obtention" id="annee_obtention" class="form-control @error('annee_obtention') is-invalid @enderror" value="{{ old('annee_obtention', $diplome->annee_obtention) }}" min="1950" max="{{ date('Y') }}" required>
                        @error('annee_obtention')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="niveau" class="form-label">Niveau <span class="text-danger">*</span></label>
                        <input type="text" name="niveau" id="niveau" class="form-control @error('niveau') is-invalid @enderror" value="{{ old('niveau', $diplome->niveau) }}" required>
                        @error('niveau')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="specialite" class="form-label">Spécialité</label>
                        <input type="text" name="specialite" id="specialite" class="form-control @error('specialite') is-invalid @enderror" value="{{ old('specialite', $diplome->specialite) }}">
                        @error('specialite')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                    <a href="{{ route('diplomes.index') }}" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
@endsection
