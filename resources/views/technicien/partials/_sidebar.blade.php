<a href="{{ route('technicien.dashboard') }}"
   class="nav-link {{ request()->routeIs('technicien.dashboard') ? 'active' : '' }}">
    <i class="bi bi-speedometer2"></i>
    <span>Dashboard</span>
</a>

<a href="{{ route('technicien.demandes') }}"
   class="nav-link {{ request()->routeIs('technicien.demandes*') ? 'active' : '' }}">
    <i class="bi bi-tools"></i>
    <span>Demandes</span>
</a>

<a href="{{ route('technicien.stock.index') }}"
   class="nav-link {{ request()->routeIs('technicien.stock*') ? 'active' : '' }}">
    <i class="bi bi-box-seam"></i>
    <span>Stock</span>
</a>