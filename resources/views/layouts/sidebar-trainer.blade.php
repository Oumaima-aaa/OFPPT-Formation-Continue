@php
    $initials = strtoupper(substr(auth()->user()->name, 0, 1).substr(strstr(auth()->user()->name, ' ') ?: auth()->user()->name, 1, 1));
    $intervenant = auth()->user()->intervenant;
@endphp

<aside class="sidebar d-none d-lg-flex flex-column">
    <div class="sidebar-brand">
        <a href="{{ route('dashboard') }}" class="sidebar-logo">
            <div class="sidebar-logo-icon"><i class="bi bi-mortarboard-fill"></i></div>
            <div class="sidebar-logo-text"><strong>OFPPT</strong><span>Espace Formateur</span></div>
        </a>
    </div>

    <nav class="sidebar-nav flex-grow-1">
        <div class="sidebar-section-label">Mon espace</div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="bi bi-speedometer2"></i> Tableau de bord
                </a>
            </li>
            @if($intervenant)
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('intervenants.edit') ? 'active' : '' }}" href="{{ route('intervenants.edit', $intervenant) }}">
                        <i class="bi bi-person-badge"></i> Mon profil
                    </a>
                </li>
            @endif
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('competences.*') ? 'active' : '' }}" href="{{ route('competences.index') }}">
                    <i class="bi bi-star"></i> Mes compétences
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('diplomes.*') ? 'active' : '' }}" href="{{ route('diplomes.index') }}">
                    <i class="bi bi-award"></i> Mes diplômes
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('certifications.*') ? 'active' : '' }}" href="{{ route('certifications.index') }}">
                    <i class="bi bi-patch-check"></i> Mes certifications
                </a>
            </li>
        </ul>

        <div class="sidebar-section-label">Formation</div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('catalog.*') ? 'active' : '' }}" href="{{ route('catalog.index') }}">
                    <i class="bi bi-journal-text"></i> Catalogue
                </a>
            </li>
            @can('viewAny', \App\Models\Plan::class)
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('plans.*') ? 'active' : '' }}" href="{{ route('plans.index') }}">
                    <i class="bi bi-calendar-check"></i> Plans
                </a>
            </li>
            @endcan
            @can('viewAny', \App\Models\Action::class)
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('actions.*') ? 'active' : '' }}" href="{{ route('actions.index') }}">
                    <i class="bi bi-play-circle"></i> Mes actions
                </a>
            </li>
            @endcan
        </ul>
    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="sidebar-user-avatar">{{ $initials }}</div>
            <div class="sidebar-user-info">
                <small>Formateur</small>
                <strong>{{ auth()->user()->name }}</strong>
            </div>
        </div>
    </div>
</aside>

<div class="offcanvas offcanvas-start sidebar-offcanvas d-lg-none" tabindex="-1" id="sidebarOffcanvas">
    <div class="offcanvas-header sidebar-brand border-0">
        <a href="{{ route('dashboard') }}" class="sidebar-logo">
            <div class="sidebar-logo-icon"><i class="bi bi-mortarboard-fill"></i></div>
            <div class="sidebar-logo-text"><strong>OFPPT</strong><span>Espace Formateur</span></div>
        </a>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body p-0">
        <nav class="sidebar-nav">
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}"><i class="bi bi-speedometer2"></i> Tableau de bord</a></li>
                @if($intervenant)
                    <li class="nav-item"><a class="nav-link" href="{{ route('intervenants.edit', $intervenant) }}"><i class="bi bi-person-badge"></i> Mon profil</a></li>
                @endif
                <li class="nav-item"><a class="nav-link" href="{{ route('competences.index') }}"><i class="bi bi-star"></i> Compétences</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('diplomes.index') }}"><i class="bi bi-award"></i> Diplômes</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('certifications.index') }}"><i class="bi bi-patch-check"></i> Certifications</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('catalog.index') }}"><i class="bi bi-journal-text"></i> Catalogue</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('actions.index') }}"><i class="bi bi-play-circle"></i> Mes actions</a></li>
            </ul>
        </nav>
    </div>
</div>
