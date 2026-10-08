@extends('layouts.app')

@section('title', 'Tableau de bord')

@php
    $header = 'Administration centrale';
    $subtitle = 'Vue d\'ensemble de la plateforme nationale';
@endphp

@section('content')
    <div class="row g-4">
        <div class="col-md-6 col-xl-3">
            <x-stat-card label="Entreprises" :value="$stats['entreprises']" icon="briefcase" color="green" :link="route('entreprises.index')" linkText="Gérer les entreprises" />
        </div>
        <div class="col-md-6 col-xl-3">
            <x-stat-card label="Actions de formation" :value="$stats['actions']" icon="play-circle" color="blue" :link="route('actions.index')" linkText="Voir les actions" />
        </div>
        <div class="col-md-6 col-xl-3">
            <x-stat-card label="Plans de formation" :value="$stats['plans']" icon="calendar-check" color="orange" :link="route('plans.index')" linkText="Voir les plans" />
        </div>
        <div class="col-md-6 col-xl-3">
            <x-stat-card label="Thèmes actifs" :value="$stats['themes']" icon="book" color="purple" :link="route('themes.index')" linkText="Voir les thèmes" />
        </div>
    </div>
@endsection
