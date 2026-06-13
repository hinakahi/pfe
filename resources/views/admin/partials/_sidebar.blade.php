@php
    $reclamationsEnAttente = \App\Models\Reclamation::where('statut', 'en_attente')->count();
    $messagesNonLus = \App\Models\ContactMessage::where('lu', false)->count();
    $nouveauxUtilisateurs = \App\Models\User::whereDate('created_at', today())->count();
@endphp

<a href="{{ route('admin.dashboard') }}" 
   class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
    <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
</a>

<a href="{{ route('admin.reclamations.index') }}" class="nav-link">
    <i class="bi bi-file-earmark-text"></i>
    <span>Réclamations</span>
    @if($reclamationsEnAttente > 0)
        <span class="badge bg-danger rounded-pill ms-auto">{{ $reclamationsEnAttente }}</span>
    @endif
</a>

<a href="{{ route('admin.statistiques') }}" class="nav-link {{ request()->routeIs('admin.statistiques') ? 'active' : '' }}">
    <i class="bi bi-pie-chart-fill"></i> <span>Statistiques</span>
</a>

<a href="{{ route('admin.utilisateurs.index') }}" 
   class="nav-link {{ request()->routeIs('admin.utilisateurs.*') ? 'active' : '' }}">
    <i class="bi bi-people"></i>
    <span>Utilisateurs</span>
    @if($nouveauxUtilisateurs > 0)
        <span class="badge bg-warning text-dark rounded-pill ms-auto">{{ $nouveauxUtilisateurs }}</span>
    @endif
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
    <i class="bi bi-envelope"></i>
    <span>Messages</span>
    @if($messagesNonLus > 0)
        <span class="badge bg-danger rounded-pill ms-auto">{{ $messagesNonLus }}</span>
    @endif
</a>