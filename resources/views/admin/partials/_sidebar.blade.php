<a href="{{ route('admin.dashboard') }}" 
   class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
    <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
</a>
<a href="{{ route('admin.reclamations.index') }}" class="nav-link">
    <i class="bi bi-file-earmark-text"></i> <span>Réclamations</span>
</a>
<a href="{{ route('admin.statistiques') }}" class="nav-link {{ request()->routeIs('admin.statistiques') ? 'active' : '' }}">
    <i class="bi bi-pie-chart-fill"></i> <span>Statistiques</span>
</a>
<a href="{{ route('admin.utilisateurs.index') }}" 
   class="nav-link {{ request()->routeIs('admin.utilisateurs.*') ? 'active' : '' }}">
    <i class="bi bi-people"></i> <span>Utilisateurs</span>
</a>
<a href="{{ route('admin.matricules.index') }}" 
   class="nav-link {{ request()->routeIs('admin.matricules.*') ? 'active' : '' }}">
    <i class="bi bi-credit-card"></i> <span>Matricules</span>
</a>
<a href="{{ route('admin.periodes.index') }}" 
   class="nav-link {{ request()->routeIs('admin.periodes.*') ? 'active' : '' }}">
    <i class="bi bi-calendar-range"></i> <span>Périodes</span>
</a>
<a href="{{ route('admin.annonces.index') }}" 
   class="nav-link {{ request()->routeIs('admin.annonces.*') ? 'active' : '' }}">
    <i class="bi bi-megaphone"></i> <span>Annonces</span>
</a>
<a href="{{ route('admin.messages') }}" class="nav-link">
    <i class="bi bi-envelope"></i> Messages
</a>


