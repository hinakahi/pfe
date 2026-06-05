<a href="{{ route('foyer.dashboard') }}"
   class="nav-link {{ request()->routeIs('foyer.dashboard') ? 'active' : '' }}">
    <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
</a>
<a href="{{ route('foyer.catalogue.index') }}"
   class="nav-link {{ request()->routeIs('foyer.catalogue.*') ? 'active' : '' }}">
    <i class="bi bi-shop"></i> <span>Catalogue</span>
</a>
<a href="{{ route('foyer.reservations') }}"
   class="nav-link {{ request()->routeIs('foyer.reservations') ? 'active' : '' }}">
    <i class="bi bi-calendar-check"></i> <span>Réservations</span>
</a>
<a href="{{ route('foyer.promotions.index') }}"
   class="nav-link {{ request()->routeIs('foyer.promotions.*') ? 'active' : '' }}">
    <i class="bi bi-megaphone"></i> <span>Annonces</span>
</a>