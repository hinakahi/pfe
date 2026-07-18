<a href="{{ route('etudiante.dashboard') }}" class="nav-link {{ request()->routeIs('etudiante.dashboard') ? 'active' : '' }}">
    <i class="bi bi-speedometer2"></i>
    <span>Dashboard</span>
</a>

<a href="{{ route('etudiante.notifications') }}" class="nav-link {{ request()->routeIs('etudiante.notifications') ? 'active' : '' }}">
    <i class="bi bi-bell"></i>
    <span>Notifications</span>
    @if(auth()->user()->unreadNotifications->count() > 0)
        <span class="badge bg-danger rounded-pill ms-auto">
            {{ auth()->user()->unreadNotifications->count() }}
        </span>
    @endif
</a>

<a href="{{ route('etudiante.annonces') }}" class="nav-link {{ request()->routeIs('etudiante.annonces') ? 'active' : '' }}">
    <i class="bi bi-megaphone"></i>
    <span>Annonces</span>
</a>

<a href="{{ route('etudiante.hebergement.index') }}" class="nav-link {{ request()->routeIs('etudiante.hebergement.*') ? 'active' : '' }}">
    <i class="bi bi-house-door"></i>
    <span>Hébergement</span>
</a>

<a href="{{ route('etudiante.foyer.dashboard') }}" class="nav-link {{ request()->routeIs('etudiante.foyer.*') ? 'active' : '' }}">
    <i class="bi bi-shop"></i>
    <span>Foyer</span>
</a>

<a href="{{ route('etudiante.maintenance.index') }}" class="nav-link {{ request()->routeIs('etudiante.maintenance.*') ? 'active' : '' }}">
    <i class="bi bi-tools"></i>
    <span>Maintenance</span>
</a>

<a href="{{ route('etudiante.reclamations.index') }}" class="nav-link {{ request()->routeIs('etudiante.reclamations.*') ? 'active' : '' }}">
    <i class="bi bi-chat-left-text"></i>
    <span>Réclamations</span>
</a>