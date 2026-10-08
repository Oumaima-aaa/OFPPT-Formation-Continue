<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'OFPPT') }} — @yield('title', 'Authentification')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/ofppt-logo.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="auth-page">
        <div class="auth-brand-panel d-none d-lg-flex">
            <div class="auth-brand-content">
                <div class="auth-brand-icon">
                    <x-ofppt-logo variant="brand" />
                </div>
                <h1>OFPPT Formation Continue</h1>
                <p>Plateforme nationale de gestion de la formation continue professionnelle</p>
                <div class="auth-features">
                    <div class="auth-feature-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Gestion des plans et actions de formation</span>
                    </div>
                    <div class="auth-feature-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Suivi multi-régional en temps réel</span>
                    </div>
                    <div class="auth-feature-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Tableaux de bord et statistiques avancées</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="auth-form-panel">
            <div class="auth-form-wrapper">
                <div class="auth-form-logo">
                    <x-ofppt-logo />
                </div>
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>
