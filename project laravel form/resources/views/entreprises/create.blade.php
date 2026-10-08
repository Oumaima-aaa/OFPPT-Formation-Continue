@extends('layouts.app')



@section('title', 'Nouvelle entreprise')



@php

    $header = 'Nouvelle entreprise';

    use App\Models\Entreprise;

@endphp



@section('content')

    <div class="card">

        <div class="card-body">

            <form action="{{ route('entreprises.store') }}" method="POST" enctype="multipart/form-data">

                @csrf



                <div class="row">

                    <div class="col-md-8 mb-3">

                        <label for="raison" class="form-label">Raison sociale <span class="text-danger">*</span></label>

                        <input type="text" name="raison" id="raison" class="form-control @error('raison') is-invalid @enderror" value="{{ old('raison') }}" required>

                        @error('raison')<div class="invalid-feedback">{{ $message }}</div>@enderror

                    </div>

                    <div class="col-md-4 mb-3">

                        <label for="logo" class="form-label">Logo</label>

                        <input type="file" name="logo" id="logo" class="form-control @error('logo') is-invalid @enderror" accept="image/*">

                        @error('logo')<div class="invalid-feedback">{{ $message }}</div>@enderror

                    </div>

                </div>



                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>

                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>

                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror

                    </div>

                    <div class="col-md-6 mb-3">

                        <label for="site" class="form-label">Site web</label>

                        <input type="url" name="site" id="site" class="form-control @error('site') is-invalid @enderror" value="{{ old('site') }}">

                        @error('site')<div class="invalid-feedback">{{ $message }}</div>@enderror

                    </div>

                </div>



                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label for="telephone1" class="form-label">Téléphone 1 <span class="text-danger">*</span></label>

                        <input type="text" name="telephone1" id="telephone1" class="form-control @error('telephone1') is-invalid @enderror" value="{{ old('telephone1') }}" required>

                        @error('telephone1')<div class="invalid-feedback">{{ $message }}</div>@enderror

                    </div>

                    <div class="col-md-4 mb-3">

                        <label for="telephone2" class="form-label">Téléphone 2</label>

                        <input type="text" name="telephone2" id="telephone2" class="form-control @error('telephone2') is-invalid @enderror" value="{{ old('telephone2') }}">

                        @error('telephone2')<div class="invalid-feedback">{{ $message }}</div>@enderror

                    </div>

                    <div class="col-md-4 mb-3">

                        <label for="telephone3" class="form-label">Téléphone 3</label>

                        <input type="text" name="telephone3" id="telephone3" class="form-control @error('telephone3') is-invalid @enderror" value="{{ old('telephone3') }}">

                        @error('telephone3')<div class="invalid-feedback">{{ $message }}</div>@enderror

                    </div>

                </div>



                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label for="representant" class="form-label">Représentant <span class="text-danger">*</span></label>

                        <input type="text" name="representant" id="representant" class="form-control @error('representant') is-invalid @enderror" value="{{ old('representant') }}" required>

                        @error('representant')<div class="invalid-feedback">{{ $message }}</div>@enderror

                    </div>

                    <div class="col-md-3 mb-3">

                        <label for="status" class="form-label">Statut <span class="text-danger">*</span></label>

                        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>

                            @foreach(Entreprise::activeStatusLabels() as $value => $label)

                                <option value="{{ $value }}" @selected((int) old('status', Entreprise::STATUS_ACTIF) === $value)>{{ $label }}</option>

                            @endforeach

                        </select>

                        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror

                    </div>

                    <div class="col-md-3 mb-3">

                        <label for="users_id" class="form-label">Utilisateur</label>

                        <select name="users_id" id="users_id" class="form-select @error('users_id') is-invalid @enderror">

                            <option value="">— Aucun —</option>

                            @foreach($users as $user)

                                <option value="{{ $user->id }}" @selected(old('users_id') == $user->id)>{{ $user->name }}</option>

                            @endforeach

                        </select>

                        @error('users_id')<div class="invalid-feedback">{{ $message }}</div>@enderror

                    </div>

                </div>



                <div class="d-flex gap-2">

                    <button type="submit" class="btn btn-primary">Enregistrer</button>

                    <a href="{{ route('entreprises.index') }}" class="btn btn-secondary">Annuler</a>

                </div>

            </form>

        </div>

    </div>

@endsection


