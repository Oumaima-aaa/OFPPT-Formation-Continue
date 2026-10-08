@extends('layouts.app')

@section('title', 'Tableau de bord')

@php
    $header = 'Tableau de bord';
@endphp

@section('content')
    <div class="card">
        <div class="card-body">
            <p class="mb-0 text-muted">Redirection gérée par le contrôleur selon votre rôle.</p>
        </div>
    </div>
@endsection
