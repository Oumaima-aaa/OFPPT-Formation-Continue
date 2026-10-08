@extends('layouts.app')

@section('title', 'Nouveau thème')

@php
    $header = 'Nouveau thème';
    $subtitle = 'Ajouter un programme au catalogue de formation';
@endphp

@section('content')
    <div class="card form-card">
        <div class="card-header"><i class="bi bi-plus-circle me-2"></i>Informations du thème</div>
        <div class="card-body">
            <form action="{{ route('themes.store') }}" method="POST">
                @csrf
                @include('themes.partials.form', ['domaines' => $domaines])

                <div class="d-flex gap-2 mt-4 pt-3 border-top">
                    <button type="submit" class="btn btn-primary btn-icon"><i class="bi bi-check-lg"></i> Enregistrer</button>
                    <a href="{{ route('themes.index') }}" class="btn btn-outline-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
@endsection
