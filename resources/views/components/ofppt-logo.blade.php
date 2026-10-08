@props(['variant' => 'login'])

<img
    src="{{ asset('images/ofppt-logo.png') }}"
    alt="OFPPT — Office de la Formation Professionnelle et de la Promotion du Travail"
    class="ofppt-logo {{ $variant === 'brand' ? 'ofppt-logo--brand' : 'ofppt-logo--login' }}"
    {{ $attributes }}
>
