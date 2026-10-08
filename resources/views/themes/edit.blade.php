@extends('layouts.app')

@section('title', 'Modifier le thème')

@php
    $header = 'Modifier le thème';
    $subtitle = $theme->intitule_theme;
@endphp

@section('content')
    <div class="card form-card">
        <div class="card-header"><i class="bi bi-pencil me-2"></i>Modifier les informations</div>
        <div class="card-body">
            <form action="{{ route('themes.update', $theme) }}" method="POST">
                @csrf
                @method('PUT')
                @include('themes.partials.form', ['theme' => $theme, 'domaines' => $domaines])

                <div class="d-flex gap-2 mt-4 pt-3 border-top">
                    <button type="submit" class="btn btn-primary btn-icon"><i class="bi bi-check-lg"></i> Mettre à jour</button>
                    <a href="{{ route('themes.show', $theme) }}" class="btn btn-outline-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
@endsection
