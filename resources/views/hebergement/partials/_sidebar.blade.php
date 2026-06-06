<a href="{{ route('hebergement.dashboard') }}" class="nav-link {{ request()->routeIs('hebergement.dashboard') ? 'active' : '' }}">
    <i class="bi bi-speedometer2"></i>
    <span>Dashboard</span>
</a>

<a href="{{ route('hebergement.chambres.index') }}" class="nav-link {{ request()->routeIs('hebergement.chambres.index') ? 'active' : '' }}">
    <i class="bi bi-door-open"></i>
    <span>Liste des chambres</span>
</a>

<a href="{{ route('hebergement.chambres.create') }}" class="nav-link {{ request()->routeIs('hebergement.chambres.create') ? 'active' : '' }}">
    <i class="bi bi-plus-circle"></i>
    <span>Ajouter une chambre</span>
</a>

<a href="{{ route('hebergement.chambres.vides') }}" class="nav-link {{ request()->routeIs('hebergement.chambres.vides') ? 'active' : '' }}">
    <i class="bi bi-eye"></i>
    <span>Chambres vides</span>
</a>

<a href="{{ route('hebergement.renouvellements.index') }}" class="nav-link {{ request()->routeIs('hebergement.renouvellements.*') ? 'active' : '' }}">
    <i class="bi bi-arrow-repeat"></i>
    <span>Renouvellements</span>
</a>

<a href="{{ route('hebergement.changements.index') }}" class="nav-link {{ request()->routeIs('hebergement.changements.*') ? 'active' : '' }}">
    <i class="bi bi-shuffle"></i>
    <span>Changements</span>
</a>