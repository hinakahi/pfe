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
<a href="{{ route('foyer.annonces.index') }}"
   class="nav-link {{ request()->routeIs('foyer.annonces.*') ? 'active' : '' }}">
    <i class="bi bi-megaphone"></i> <span>Annonces</span>
</a>
{{-- 🔔 Notifications --}}
@php $notifs = auth()->user()->unreadNotifications->take(5); @endphp

<div class="nav-link position-relative" style="cursor:pointer" data-bs-toggle="collapse" data-bs-target="#notifPanel">
    <i class="bi bi-bell"></i>
    <span>Notifications</span>
    @if($notifs->count() > 0)
        <span class="badge bg-danger rounded-pill ms-auto">{{ $notifs->count() }}</span>
    @endif
</div>

<div class="collapse {{ $notifs->count() > 0 ? 'show' : '' }}" id="notifPanel">
    <div class="mx-2 mb-2" style="max-height:300px; overflow-y:auto;">
        @forelse($notifs as $notif)
            <div class="p-2 mb-1 rounded small
                {{ $notif->data['type'] === 'nouvelle_commande' ? 'bg-primary bg-opacity-10 text-primary' : 'bg-secondary bg-opacity-10' }}">
                <div>{{ $notif->data['message'] }}</div>
                <div class="text-muted" style="font-size:0.75rem">{{ $notif->created_at->diffForHumans() }}</div>
            </div>
        @empty
            <div class="text-muted small p-2">Aucune nouvelle notification</div>
        @endforelse

        @if($notifs->count() > 0)
            <form method="POST" action="{{ route('foyer.notifications.markAllRead') }}">
                @csrf
                <button class="btn btn-sm btn-outline-secondary w-100 mt-1">
                    Tout marquer comme lu
                </button>
            </form>
        @endif
    </div>
</div>