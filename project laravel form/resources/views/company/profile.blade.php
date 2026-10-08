@extends('layouts.app')

@section('title', 'Mon profil entreprise')

@php
    $header = 'Mon profil entreprise';
    $subtitle = 'Mettez à jour les informations de votre entreprise';
@endphp

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('company.profile.update') }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="raison" class="form-label">Nom de l'entreprise <span class="text-danger">*</span></label>
                        <input type="text" name="raison" id="raison" class="form-control @error('raison') is-invalid @enderror" value="{{ old('raison', $entreprise->raison) }}" required>
                        @error('raison')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="ice" class="form-label">ICE</label>
                        <input type="text" name="ice" id="ice" class="form-control @error('ice') is-invalid @enderror" value="{{ old('ice', $entreprise->ice) }}">
                        @error('ice')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="adresse" class="form-label">Adresse</label>
                    <input type="text" name="adresse" id="adresse" class="form-control @error('adresse') is-invalid @enderror" value="{{ old('adresse', $entreprise->adresse) }}">
                    @error('adresse')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="telephone1" class="form-label">Téléphone <span class="text-danger">*</span></label>
                        <input type="text" name="telephone1" id="telephone1" class="form-control @error('telephone1') is-invalid @enderror" value="{{ old('telephone1', $entreprise->telephone1) }}" required>
                        @error('telephone1')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">E-mail entreprise <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $entreprise->email) }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="representant" class="form-label">Personne de contact <span class="text-danger">*</span></label>
                        <input type="text" name="representant" id="representant" class="form-control @error('representant') is-invalid @enderror" value="{{ old('representant', $entreprise->representant) }}" required>
                        @error('representant')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="contact_email" class="form-label">E-mail de connexion <span class="text-danger">*</span></label>
                        <input type="email" name="contact_email" id="contact_email" class="form-control @error('contact_email') is-invalid @enderror" value="{{ old('contact_email', $user->email) }}" required>
                        @error('contact_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
@endsection
