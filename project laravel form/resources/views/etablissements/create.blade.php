@extends('layouts.app')



@section('title', 'Nouvel établissement')



@php

    $header = 'Nouvel établissement';

    use App\Models\Etablissement;

@endphp



@section('content')

    <div class="card">

        <div class="card-body">

            <form action="{{ route('etablissements.store') }}" method="POST">

                @csrf



                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label for="nom_efp" class="form-label">Nom EFP <span class="text-danger">*</span></label>

                        <input type="text" name="nom_efp" id="nom_efp" class="form-control @error('nom_efp') is-invalid @enderror" value="{{ old('nom_efp') }}" required>

                        @error('nom_efp')<div class="invalid-feedback">{{ $message }}</div>@enderror

                    </div>

                    <div class="col-md-6 mb-3">

                        <label for="ville" class="form-label">Ville <span class="text-danger">*</span></label>

                        <input type="text" name="ville" id="ville" class="form-control @error('ville') is-invalid @enderror" value="{{ old('ville') }}" required>

                        @error('ville')<div class="invalid-feedback">{{ $message }}</div>@enderror

                    </div>

                </div>



                <div class="mb-3">

                    <label for="adresse" class="form-label">Adresse <span class="text-danger">*</span></label>

                    <textarea name="adresse" id="adresse" rows="2" class="form-control @error('adresse') is-invalid @enderror" required>{{ old('adresse') }}</textarea>

                    @error('adresse')<div class="invalid-feedback">{{ $message }}</div>@enderror

                </div>



                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label for="tel" class="form-label">Téléphone <span class="text-danger">*</span></label>

                        <input type="text" name="tel" id="tel" class="form-control @error('tel') is-invalid @enderror" value="{{ old('tel') }}" required>

                        @error('tel')<div class="invalid-feedback">{{ $message }}</div>@enderror

                    </div>

                    <div class="col-md-4 mb-3">

                        <label for="regions_id" class="form-label">Région <span class="text-danger">*</span></label>

                        <select name="regions_id" id="regions_id" class="form-select @error('regions_id') is-invalid @enderror" required>

                            <option value="">— Sélectionner —</option>

                            @foreach($regions as $region)

                                <option value="{{ $region->id }}" @selected(old('regions_id') == $region->id)>{{ $region->nom_region }}</option>

                            @endforeach

                        </select>

                        @error('regions_id')<div class="invalid-feedback">{{ $message }}</div>@enderror

                    </div>

                    <div class="col-md-4 mb-3">

                        <label for="status" class="form-label">Statut <span class="text-danger">*</span></label>

                        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>

                            @foreach(Etablissement::activeStatusLabels() as $value => $label)

                                <option value="{{ $value }}" @selected((int) old('status', Etablissement::STATUS_ACTIF) === $value)>{{ $label }}</option>

                            @endforeach

                        </select>

                        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror

                    </div>

                </div>



                <div class="mb-3">

                    <label for="users_id" class="form-label">Administrateur local</label>

                    <select name="users_id" id="users_id" class="form-select @error('users_id') is-invalid @enderror">

                        <option value="">— Aucun —</option>

                        @foreach($users as $user)

                            <option value="{{ $user->id }}" @selected(old('users_id') == $user->id)>{{ $user->name }} ({{ $user->email }})</option>

                        @endforeach

                    </select>

                    @error('users_id')<div class="invalid-feedback">{{ $message }}</div>@enderror

                </div>



                <div class="d-flex gap-2">

                    <button type="submit" class="btn btn-primary">Enregistrer</button>

                    <a href="{{ route('etablissements.index') }}" class="btn btn-secondary">Annuler</a>

                </div>

            </form>

        </div>

    </div>

@endsection


