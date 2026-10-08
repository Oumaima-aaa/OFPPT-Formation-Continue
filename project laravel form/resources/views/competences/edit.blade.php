@extends('layouts.app')

@section('title', 'Modifier la compétence')

@php
    $header = 'Modifier la compétence';
@endphp

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('competences.update', $competence) }}" method="POST">
                @csrf
                @method('PUT')

                @unless(auth()->user()->isTrainer())
                <div class="mb-3">
                    <label for="intervenant_id" class="form-label">Intervenant <span class="text-danger">*</span></label>
                    <select name="intervenant_id" id="intervenant_id" class="form-select @error('intervenant_id') is-invalid @enderror" required>
                        <option value="">— Sélectionner —</option>
                        @foreach($intervenants as $intervenant)
                            <option value="{{ $intervenant->id }}" @selected(old('intervenant_id', $competence->intervenant_id) == $intervenant->id)>{{ $intervenant->prenom }} {{ $intervenant->nom }}</option>
                        @endforeach
                    </select>
                    @error('intervenant_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                @endunless

                <div class="mb-3">
                    <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                    <input type="text" name="nom" id="nom" class="form-control @error('nom') is-invalid @enderror" value="{{ old('nom', $competence->nom) }}" required>
                    @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label for="niveau" class="form-label">Niveau <span class="text-danger">*</span></label>
                    <select name="niveau" id="niveau" class="form-select @error('niveau') is-invalid @enderror" required>
                        <option value="debutant" @selected(old('niveau', $competence->niveau) === 'debutant')>Débutant</option>
                        <option value="intermediaire" @selected(old('niveau', $competence->niveau) === 'intermediaire')>Intermédiaire</option>
                        <option value="expert" @selected(old('niveau', $competence->niveau) === 'expert')>Expert</option>
                    </select>
                    @error('niveau')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', $competence->description) }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                    <a href="{{ route('competences.index') }}" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
@endsection
