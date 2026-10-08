@php
    $initials = strtoupper(substr(auth()->user()->name, 0, 1).substr(strstr(auth()->user()->name, ' ') ?: auth()->user()->name, 1, 1));
    $roleName = auth()->user()->roles->first()?->name ?? 'Utilisateur';
    use App\Models\Action;
    use App\Models\Domaine;
    use App\Models\Etablissement;
    use App\Models\Plan;
    use App\Models\Region;
    use App\Models\Theme;
@endphp

<aside class="sidebar d-none d-lg-flex flex-column">
    <div class="sidebar-brand">
        <a href="{{ route('dashboard') }}" class="sidebar-logo">
            <div class="sidebar-logo-icon">
                <i class="bi bi-mortarboard-fill"></i>
            </div>
            <div class="sidebar-logo-text">
                <strong>OFPPT</strong>
                <span>Formation Continue</span>
            </div>
        </a>
    </div>

    <nav class="sidebar-nav flex-grow-1">
        <div class="sidebar-section-label">Navigation</div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="bi bi-speedometer2"></i> Tableau de bord
                </a>
            </li>
            @can('viewAny', Region::class)
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('regions.*') ? 'active' : '' }}" href="{{ route('regions.index') }}">
                    <i class="bi bi-geo-alt"></i> Régions
                </a>
            </li>
            @endcan
            @can('viewAny', Domaine::class)
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('domaines.*') ? 'active' : '' }}" href="{{ route('domaines.index') }}">
                    <i class="bi bi-grid-1x2"></i> Domaines
                </a>
            </li>
            @endcan
            @can('viewAny', Theme::class)
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('themes.*') ? 'active' : '' }}" href="{{ route('themes.index') }}">
                    <i class="bi bi-book"></i> Thèmes
                </a>
            </li>
            @endcan
            @can('viewAny', Etablissement::class)
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('etablissements.*') ? 'active' : '' }}" href="{{ route('etablissements.index') }}">
                    <i class="bi bi-building"></i> Établissements
                </a>
            </li>
            @endcan
            @can('entreprises.manage')
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('entreprises.*') ? 'active' : '' }}" href="{{ route('entreprises.index') }}">
                    <i class="bi bi-briefcase"></i> Entreprises
                </a>
            </li>
            @endcan
        </ul>

        <div class="sidebar-section-label">Formation</div>
        <ul class="nav flex-column">
            @can('catalog.view')
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('catalog.*') ? 'active' : '' }}" href="{{ route('catalog.index') }}">
                    <i class="bi bi-journal-text"></i> Catalogue
                </a>
            </li>
            @endcan
            @can('viewAny', Plan::class)
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('plans.*') ? 'active' : '' }}" href="{{ route('plans.index') }}">
                    <i class="bi bi-calendar-check"></i> Plans
                </a>
            </li>
            @endcan
            @can('viewAny', Action::class)
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('actions.*') ? 'active' : '' }}" href="{{ route('actions.index') }}">
                    <i class="bi bi-play-circle"></i> Actions
                </a>
            </li>
            @endcan
            @can('statistics.view')
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('statistics.*') ? 'active' : '' }}" href="{{ route('statistics.index') }}">
                    <i class="bi bi-bar-chart-line"></i> Statistiques
                </a>
            </li>
            @endcan
        </ul>

        @can('users.view')
        <div class="sidebar-section-label">Administration</div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                    <i class="bi bi-people"></i> Utilisateurs
                </a>
            </li>
            @can('create', \App\Models\User::class)
            <li class="nav-item">
                <a class="nav-link sidebar-sub {{ request()->routeIs('users.create') ? 'active' : '' }}" href="{{ route('users.create') }}">
                    <i class="bi bi-person-plus"></i> Ajouter un utilisateur
                </a>
            </li>
            @endcan
        </ul>
        @endcan

        @can('intervenants.manage')
        <div class="sidebar-section-label">Intervenants</div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('intervenants.*') ? 'active' : '' }}" href="{{ route('intervenants.index') }}">
                    <i class="bi bi-people"></i> Intervenants
                </a>
            </li>
            @can('competences.manage')
            <li class="nav-item">
                <a class="nav-link sidebar-sub {{ request()->routeIs('competences.*') ? 'active' : '' }}" href="{{ route('competences.index') }}">
                    <i class="bi bi-star"></i> Compétences
                </a>
            </li>
            @endcan
            @can('diplomes.manage')
            <li class="nav-item">
                <a class="nav-link sidebar-sub {{ request()->routeIs('diplomes.*') ? 'active' : '' }}" href="{{ route('diplomes.index') }}">
                    <i class="bi bi-award"></i> Diplômes
                </a>
            </li>
            @endcan
            @can('certifications.manage')
            <li class="nav-item">
                <a class="nav-link sidebar-sub {{ request()->routeIs('certifications.*') ? 'active' : '' }}" href="{{ route('certifications.index') }}">
                    <i class="bi bi-patch-check"></i> Certifications
                </a>
            </li>
            @endcan
        </ul>
        @endcan
    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="sidebar-user-avatar">{{ $initials }}</div>
            <div class="sidebar-user-info">
                <small>{{ $roleName }}</small>
                <strong>{{ auth()->user()->name }}</strong>
            </div>
        </div>
    </div>
</aside>

<div class="offcanvas offcanvas-start sidebar-offcanvas d-lg-none" tabindex="-1" id="sidebarOffcanvas">
    <div class="offcanvas-header sidebar-brand border-0">
        <a href="{{ route('dashboard') }}" class="sidebar-logo">
            <div class="sidebar-logo-icon"><i class="bi bi-mortarboard-fill"></i></div>
            <div class="sidebar-logo-text"><strong>OFPPT</strong><span>Formation Continue</span></div>
        </a>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body p-0">
        <nav class="sidebar-nav">
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><i class="bi bi-speedometer2"></i> Tableau de bord</a></li>
                @can('viewAny', Region::class)<li class="nav-item"><a class="nav-link {{ request()->routeIs('regions.*') ? 'active' : '' }}" href="{{ route('regions.index') }}"><i class="bi bi-geo-alt"></i> Régions</a></li>@endcan
                @can('viewAny', Domaine::class)<li class="nav-item"><a class="nav-link {{ request()->routeIs('domaines.*') ? 'active' : '' }}" href="{{ route('domaines.index') }}"><i class="bi bi-grid-1x2"></i> Domaines</a></li>@endcan
                @can('viewAny', Theme::class)<li class="nav-item"><a class="nav-link {{ request()->routeIs('themes.*') ? 'active' : '' }}" href="{{ route('themes.index') }}"><i class="bi bi-book"></i> Thèmes</a></li>@endcan
                @can('viewAny', Etablissement::class)<li class="nav-item"><a class="nav-link {{ request()->routeIs('etablissements.*') ? 'active' : '' }}" href="{{ route('etablissements.index') }}"><i class="bi bi-building"></i> Établissements</a></li>@endcan
                @can('entreprises.manage')<li class="nav-item"><a class="nav-link {{ request()->routeIs('entreprises.*') ? 'active' : '' }}" href="{{ route('entreprises.index') }}"><i class="bi bi-briefcase"></i> Entreprises</a></li>@endcan
                @can('catalog.view')<li class="nav-item"><a class="nav-link {{ request()->routeIs('catalog.*') ? 'active' : '' }}" href="{{ route('catalog.index') }}"><i class="bi bi-journal-text"></i> Catalogue</a></li>@endcan
                @can('viewAny', Plan::class)<li class="nav-item"><a class="nav-link {{ request()->routeIs('plans.*') ? 'active' : '' }}" href="{{ route('plans.index') }}"><i class="bi bi-calendar-check"></i> Plans</a></li>@endcan
                @can('viewAny', Action::class)<li class="nav-item"><a class="nav-link {{ request()->routeIs('actions.*') ? 'active' : '' }}" href="{{ route('actions.index') }}"><i class="bi bi-play-circle"></i> Actions</a></li>@endcan
                @can('intervenants.manage')<li class="nav-item"><a class="nav-link {{ request()->routeIs('intervenants.*') ? 'active' : '' }}" href="{{ route('intervenants.index') }}"><i class="bi bi-people"></i> Intervenants</a></li>@endcan
                @can('statistics.view')<li class="nav-item"><a class="nav-link {{ request()->routeIs('statistics.*') ? 'active' : '' }}" href="{{ route('statistics.index') }}"><i class="bi bi-bar-chart-line"></i> Statistiques</a></li>@endcan
                @can('users.view')<li class="nav-item"><a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}"><i class="bi bi-people"></i> Utilisateurs</a></li>@endcan
            </ul>
        </nav>
    </div>
</div>
