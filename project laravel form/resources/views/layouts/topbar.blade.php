@php
    $initials = strtoupper(substr(auth()->user()->name, 0, 1).substr(strstr(auth()->user()->name, ' ') ?: auth()->user()->name, 1, 1));
@endphp

<header class="topbar">
    <div class="topbar-left">
        <button class="topbar-menu-btn d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas" aria-label="Menu">
            <i class="bi bi-list fs-5"></i>
        </button>
        <div>
            <div class="topbar-breadcrumb">OFPPT · Formation Continue</div>
            <div class="topbar-title">Plateforme de gestion</div>
        </div>
    </div>

    <div class="topbar-right">
        <a href="{{ route('notifications.index') }}" class="topbar-icon-btn" title="Notifications">
            <i class="bi bi-bell"></i>
            @if(auth()->user()->unreadNotifications->count())
                <span class="topbar-badge">{{ auth()->user()->unreadNotifications->count() }}</span>
            @endif
        </a>

        <div class="dropdown">
            <div class="topbar-user-btn" data-bs-toggle="dropdown">
                <div class="topbar-user-avatar">{{ $initials }}</div>
                <span class="topbar-user-name">{{ Auth::user()->name }}</span>
                <i class="bi bi-chevron-down text-muted" style="font-size:0.7rem"></i>
            </div>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ auth()->user()->isCompany() ? route('company.profile.edit') : route('profile.edit') }}"><i class="bi bi-person me-2"></i>Mon profil</a></li>
                <li><a class="dropdown-item" href="{{ route('notifications.index') }}"><i class="bi bi-bell me-2"></i>Notifications</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Déconnexion</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>
