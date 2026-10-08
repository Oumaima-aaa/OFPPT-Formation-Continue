@extends('layouts.app')

@section('title', 'Nouvelle certification')

@php
    $header = 'Nouvelle certification';
@endphp

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('certifications.store') }}" method="POST">
                @csrf

                @unless(auth()->user()->isTrainer())
                <div class="mb-3">
                    <label for="intervenant_id" class="form-label">Intervenant <span class="text-danger">*</span></label>
                    <select name="intervenant_id" id="intervenant_id" class="form-select @error('intervenant_id') is-invalid @enderror" required>
                        <option value="">— Sélectionner —</option>
                        @foreach($intervenants as $intervenant)
                            <option value="{{ $intervenant->id }}" @selected(old('intervenant_id') == $intervenant->id)>{{ $intervenant->prenom }} {{ $intervenant->nom }}</option>
                        @endforeach
                    </select>
                    @error('intervenant_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                @endunless

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="code" class="form-label">Code</label>
                        <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code') }}">
                        @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-8 mb-3">
                        <label for="intitule" class="form-label">Intitulé <span class="text-danger">*</span></label>
                        <input type="text" name="intitule" id="intitule" class="form-control @error('intitule') is-invalid @enderror" value="{{ old('intitule') }}" required>
                        @error('intitule')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="type" class="form-label">Type</label>
                        <input type="text" name="type" id="type" class="form-control @error('type') is-invalid @enderror" value="{{ old('type') }}">
                        @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="domaine" class="form-label">Domaine</label>
                        <input type="text" name="domaine" id="domaine" class="form-control @error('domaine') is-invalid @enderror" value="{{ old('domaine') }}">
                        @error('domaine')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="organisme" class="form-label">Organisme délivrant</label>
                        <input type="text" name="organisme" id="organisme" class="form-control @error('organisme') is-invalid @enderror" value="{{ old('organisme') }}">
                        @error('organisme')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="date_obtention" class="form-label">Date d'obtention</label>
                        <input type="date" name="date_obtention" id="date_obtention" class="form-control @error('date_obtention') is-invalid @enderror" value="{{ old('date_obtention') }}">
                        @error('date_obtention')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                    <a href="{{ route('certifications.index') }}" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
@endsection
