@extends('layouts.guest')

@section('title', 'Connexion')

@section('content')
<h2>Connexion</h2>
<p class="auth-subtitle">Accédez à votre espace de gestion</p>

<div class="auth-form-card">
    @if (session('status'))
        <div class="alert alert-success"><i class="bi bi-check-circle me-1"></i>{{ session('status') }}</div>
    @endif

    @if (session('error') || request()->query('expired'))
        <div class="alert alert-warning"><i class="bi bi-exclamation-triangle me-1"></i>Session expirée. Réessayez de vous connecter.</div>
    @endif

    <form method="POST" action="/login">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Adresse e-mail</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus placeholder="nom@exemple.ma">
            </div>
            @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required placeholder="••••••••">
            </div>
            @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <div class="mb-4 form-check">
            <input type="checkbox" name="remember" id="remember" class="form-check-input">
            <label for="remember" class="form-check-label">Se souvenir de moi</label>
        </div>

        <button type="submit" class="btn btn-primary w-100 btn-icon py-2">
            <i class="bi bi-box-arrow-in-right"></i> Se connecter
        </button>

        @if (Route::has('password.request'))
            <div class="mt-3 text-center">
                <a href="{{ route('password.request') }}" class="text-decoration-none small">Mot de passe oublié ?</a>
            </div>
        @endif
    </form>
</div>

<p class="text-center text-muted small mt-4 mb-0">&copy; {{ date('Y') }} OFPPT — Formation Continue</p>
@endsection
