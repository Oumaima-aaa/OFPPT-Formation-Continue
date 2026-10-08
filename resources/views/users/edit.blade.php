@extends('layouts.app')

@section('title', 'Modifier utilisateur')

@php
    $header = 'Modifier utilisateur';
    $subtitle = $user->full_name;
@endphp

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('users.update', $user) }}">
                @csrf
                @method('PUT')
                @include('users.partials.form', ['user' => $user, 'roles' => $roles])

                <div class="row g-3 mt-1">
                    <div class="col-md-6">
                        <label for="password" class="form-label">Nouveau mot de passe</label>
                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Laisser vide pour conserver">
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary btn-icon"><i class="bi bi-check-lg"></i> Mettre à jour</button>
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Annuler</a>
                </div>
            @csrf
</form>
        </div>
    </div>
@endsection
