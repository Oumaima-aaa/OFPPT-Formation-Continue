@props(['label', 'value', 'icon', 'color' => 'green', 'link' => null, 'linkText' => 'Voir les détails'])

<div class="stat-card stat-card--{{ $color }}">
    <div class="stat-card-icon">
        <i class="bi bi-{{ $icon }}"></i>
    </div>
    <div class="stat-card-label">{{ $label }}</div>
    <div class="stat-card-value">{{ $value }}</div>
    @if($link)
        <a href="{{ $link }}" class="stat-card-link">
            {{ $linkText }} <i class="bi bi-arrow-right"></i>
        </a>
    @endif
</div>
