@extends('layouts.app')

@section('page-title', 'Notifications')

@section('sidebar')
    @include('foyer.partials._sidebar')
@endsection

@section('content')
<div class="container" style="margin-top: 2rem;">
    <h5 class="fw-bold mb-4"><i class="bi bi-bell"></i> Mes Notifications</h5>
    <div class="card mb-4">
    <div class="card-body py-3">
        <div class="row g-2 align-items-center">

            <div class="col-12 col-md-7">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" id="notifSearch"
                           class="form-control border-start-0 ps-0"
                           placeholder="Rechercher une notification…"
                           oninput="notifFilter()">
                </div>
            </div>

            <div class="col-8 col-md-3">
                <select id="notifStatutFilter" class="form-select" onchange="notifFilter()">
                    <option value="all">Toutes</option>
                    <option value="unread">Non lues</option>
                    <option value="read">Lues</option>
                </select>
            </div>

            <div class="col-4 col-md-2 text-end">
                <button class="btn btn-outline-secondary w-100" onclick="resetNotifFiltres()" title="Réinitialiser">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

        </div>
    </div>
</div>

    @if($notifications->count() > 0)
        <div class="row g-3">
            @foreach($notifications as $notif)
            @php
    $titre   = $notif->data['title'] ?? 'Nouvelle annonce';
    $message = $notif->data['message'] ?? 'Cliquez pour voir les annonces.';
@endphp
<div class="col-md-12 notif-item"
     data-statut="{{ $notif->read_at ? 'read' : 'unread' }}"
     data-texte="{{ strtolower($titre . ' ' . $message) }}">
                <div class="card border-0 shadow-sm" style="border-left: 4px solid {{ $notif->read_at ? '#ccc' : '#0d6efd' }} !important; border-radius: 10px; opacity: {{ $notif->read_at ? '0.7' : '1' }};">
                    <div class="card-body d-flex justify-content-between align-items-center gap-3">
                        @php
    $lienNotif = $notif->data['url'] ?? match($notif->data['type'] ?? null) {
        'nouvelle_commande' => '/foyer/reservations',
        default             => route('foyer.annonces.index', ['tab' => 'admin']),
    };
@endphp
<a href="{{ $lienNotif }}" class="text-decoration-none text-dark" style="flex:1;">
                            @if(!$notif->read_at)
                                <span class="badge bg-primary mb-1">Nouveau</span>
                            @endif
                            <h6 class="fw-bold mb-1">{{ $titre }}</h6>
<p class="text-muted mb-1" style="font-size:0.9rem;">{{ Str::limit($message, 120) }}</p>
                            <small class="text-muted">{{ $notif->created_at->diffForHumans() }}</small>
                        </a>

                        @if(!$notif->read_at)
                        <form method="POST" action="{{ route('foyer.notifications.read', $notif->id) }}" class="flex-shrink-0">
                            @csrf
                            <button class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-check2"></i> Lu
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div id="notif-no-result" style="display:none;">
            <div class="card">
                <div class="card-body text-center text-muted py-5">
                    <i class="bi bi-search fs-1 opacity-25"></i>
                    <p class="mt-2 mb-0">Aucune notification ne correspond à votre recherche.</p>
                </div>
            </div>
        </div>
        <div class="mt-4">{{ $notifications->links() }}</div>
    @else
        <div class="card text-center shadow-sm border-0">
            <div class="card-body py-5">
                <i class="bi bi-bell-slash" style="font-size: 3rem; color: #ccc;"></i>
                <p class="mt-3 text-muted">Aucune notification</p>
            </div>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
function notifFilter() {
    const q      = document.getElementById('notifSearch').value.toLowerCase().trim();
    const statut = document.getElementById('notifStatutFilter').value;
    const items  = document.querySelectorAll('.notif-item');
    let visible  = 0;

    items.forEach(item => {
        const matchTexte  = !q || item.dataset.texte.includes(q);
        const matchStatut = statut === 'all' || item.dataset.statut === statut;
        const show = matchTexte && matchStatut;
        item.style.display = show ? '' : 'none';
        if (show) visible++;
    });

    document.getElementById('notif-no-result').style.display = visible === 0 ? 'block' : 'none';
}

function resetNotifFiltres() {
    document.getElementById('notifSearch').value = '';
    document.getElementById('notifStatutFilter').value = 'all';
    notifFilter();
}
</script>
@endsection