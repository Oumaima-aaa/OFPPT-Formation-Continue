@php
    $initials = strtoupper(substr(auth()->user()->name, 0, 1).substr(strstr(auth()->user()->name, ' ') ?: auth()->user()->name, 1, 1));
@endphp

<aside class="sidebar d-none d-lg-flex flex-column">
    <div class="sidebar-brand">
        <a href="{{ route('dashboard') }}" class="sidebar-logo">
            <div class="sidebar-logo-icon"><i class="bi bi-mortarboard-fill"></i></div>
            <div class="sidebar-logo-text"><strong>OFPPT</strong><span>Espace Entreprise</span></div>
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
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('company.profile*') ? 'active' : '' }}" href="{{ route('company.profile.edit') }}">
                    <i class="bi bi-building"></i> Mon profil
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('catalog.*') ? 'active' : '' }}" href="{{ route('catalog.index') }}">
                    <i class="bi bi-book"></i> Catalogue formations
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('plans.*') ? 'active' : '' }}" href="{{ route('plans.index') }}">
                    <i class="bi bi-calendar-check"></i> Mes plans
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('actions.*') ? 'active' : '' }}" href="{{ route('actions.index') }}">
                    <i class="bi bi-play-circle"></i> Mes actions
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('statistics.*') ? 'active' : '' }}" href="{{ route('statistics.index') }}">
                    <i class="bi bi-bar-chart-line"></i> Mes statistiques
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}" href="{{ route('notifications.index') }}">
                    <i class="bi bi-bell"></i> Notifications
                    @if(auth()->user()->unreadNotifications->count())
                        <span class="badge bg-danger ms-1">{{ auth()->user()->unreadNotifications->count() }}</span>
                    @endif
                </a>
            </li>
        </ul>
    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="sidebar-user-avatar">{{ $initials }}</div>
            <div class="sidebar-user-info">
                <small>Entreprise</small>
                <strong>{{ auth()->user()->entreprise?->raison ?? auth()->user()->name }}</strong>
            </div>
        </div>
    </div>
</aside>

<div class="offcanvas offcanvas-start sidebar-offcanvas d-lg-none" tabindex="-1" id="sidebarOffcanvas">
    <div class="offcanvas-header sidebar-brand border-0">
        <a href="{{ route('dashboard') }}" class="sidebar-logo">
            <div class="sidebar-logo-icon"><i class="bi bi-mortarboard-fill"></i></div>
            <div class="sidebar-logo-text"><strong>OFPPT</strong><span>Espace Entreprise</span></div>
        </a>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body p-0">
        <nav class="sidebar-nav">
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}"><i class="bi bi-speedometer2"></i> Tableau de bord</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('company.profile.edit') }}"><i class="bi bi-building"></i> Mon profil</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('catalog.index') }}"><i class="bi bi-book"></i> Catalogue</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('plans.index') }}"><i class="bi bi-calendar-check"></i> Mes plans</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('actions.index') }}"><i class="bi bi-play-circle"></i> Mes actions</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('statistics.index') }}"><i class="bi bi-bar-chart-line"></i> Statistiques</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('notifications.index') }}"><i class="bi bi-bell"></i> Notifications</a></li>
            </ul>
        </nav>
    </div>
</div>
