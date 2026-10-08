@extends('layouts.guest')

@section('title', 'Mot de passe oublié')

@section('content')
    <h2 class="h4 mb-4 text-center">Mot de passe oublié</h2>

    <p class="text-muted mb-4">
        Indiquez votre adresse e-mail et nous vous enverrons un lien pour réinitialiser votre mot de passe.
    </p>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Adresse e-mail</label>
            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary">Envoyer le lien</button>
        </div>

        <div class="mt-3 text-center">
            <a href="{{ route('login') }}" class="text-decoration-none">Retour à la connexion</a>
        </div>
    </form>
@endsection
