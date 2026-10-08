<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'OFPPT') }} — @yield('title', 'Accueil')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/ofppt-logo.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    @auth
    <div class="app-wrapper">
        @include(match (true) {
            auth()->user()->isCompany() => 'layouts.sidebar-company',
            auth()->user()->isTrainer() => 'layouts.sidebar-trainer',
            default => 'layouts.sidebar',
        })

        <div class="main-content">
            @include('layouts.topbar')

            <main class="content-area">
                @include('partials.flash')

                @isset($header)
                    <div class="page-header">
                        <div>
                            <h1>{{ $header }}</h1>
                            @isset($subtitle)
                                <p class="page-subtitle mb-0">{{ $subtitle }}</p>
                            @endisset
                        </div>
                        @isset($headerActions)
                            <div class="page-header-actions">{!! $headerActions !!}</div>
                        @endisset
                    </div>
                @endisset

                @yield('content')
                {{ $slot ?? '' }}
            </main>
        </div>
    </div>
    @else
    @yield('content')
    {{ $slot ?? '' }}
    @endauth

    @stack('scripts')
</body>
</html>
