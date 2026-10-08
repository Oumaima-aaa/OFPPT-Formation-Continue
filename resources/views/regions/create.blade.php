@extends('layouts.app')



@section('title', 'Nouvelle région')



@php

    $header = 'Nouvelle région';

@endphp



@section('content')

    <div class="card">

        <div class="card-body">

            <form action="{{ route('regions.store') }}" method="POST">

                @csrf



                <div class="mb-3">

                    <label for="nom_region" class="form-label">Nom de la région <span class="text-danger">*</span></label>

                    <input type="text" name="nom_region" id="nom_region" class="form-control @error('nom_region') is-invalid @enderror" value="{{ old('nom_region') }}" required>

                    @error('nom_region')

                        <div class="invalid-feedback">{{ $message }}</div>

                    @enderror

                </div>



                <div class="mb-3">

                    <label for="users_id" class="form-label">Administrateur régional</label>

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

                    <a href="{{ route('regions.index') }}" class="btn btn-secondary">Annuler</a>

                </div>

            </form>

        </div>

    </div>

@endsection


