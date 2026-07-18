@extends('layouts.app')

@section('page-title', 'Notifications')

@section('styles')
<style>
    [data-theme="dark"] .text-muted {
        color: var(--text-muted) !important;
    }
    [data-theme="dark"] .modal-content {
        background-color: var(--bg-card);
        color: var(--text-main);
    }
    [data-theme="dark"] .modal-header,
    [data-theme="dark"] .modal-footer {
        border-color: #444;
    }
    [data-theme="dark"] .btn-close {
        filter: invert(1);
    }
</style>
@endsection

@section('sidebar')
    @include('etudiante.partials._sidebar')
@endsection

@section('content')
<div class="container" style="margin-top: 2rem;">
   

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
                    $titre   = $notif->data['title'] ?? 'Notification';
                    $message = $notif->data['message'] ?? 'Cliquez pour voir les détails.';
                @endphp

                <div class="col-md-12 notif-item"
                     data-statut="{{ $notif->read_at ? 'read' : 'unread' }}"
                     data-texte="{{ strtolower($titre . ' ' . $message) }}">

                    <div class="card border-0 shadow-sm {{ !$notif->read_at ? 'border-primary' : '' }}"
                         style="border-left: 4px solid {{ $notif->read_at ? '#ccc' : '#0d6efd' }} !important; border-radius: 10px; opacity: {{ $notif->read_at ? '0.7' : '1' }};">
                        <div class="card-body d-flex justify-content-between align-items-center gap-3">

                            <div style="flex:1;">
                                @if(!$notif->read_at)
                                    <span class="badge bg-danger me-2">Nouveau</span>
                                @endif

                                <strong>{{ $titre }}</strong>
                                <p class="text-muted mb-1" style="font-size:0.9rem;">{{ Str::limit($message, 120) }}</p>
                                <small class="text-muted">{{ $notif->created_at->diffForHumans() }}</small>
                            </div>

                            <button class="btn btn-primary flex-shrink-0"
                                    data-bs-toggle="modal"
                                    data-bs-target="#notification{{ $notif->id }}">
                                Voir
                            </button>

                        </div>
                    </div>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="notification{{ $notif->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h5 class="modal-title">{{ $titre }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                <p>{{ $message }}</p>
                                <small class="text-muted">{{ $notif->created_at->format('d/m/Y H:i') }}</small>
                            </div>

                            <div class="modal-footer">
                                @php
                                    $lienNotif = $notif->data['url'] ?? match($notif->data['type'] ?? null) {
                                        'maintenance' => route('etudiante.maintenance.index'),
                                        default       => route('etudiante.notifications'),
                                    };
                                @endphp

                                <a href="{{ $lienNotif }}" class="btn btn-outline-primary me-auto">
                                    Voir plus
                                </a>

                                @if(!$notif->read_at)
                                    <form action="{{ route('etudiante.notifications.read', $notif->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success">
                                            ✓ Marquer comme lu
                                        </button>
                                    </form>
                                @else
                                    <span class="badge bg-success">✓ Déjà lue</span>
                                @endif
                            </div>

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

        <div class="mt-4">
            {{ $notifications->links() }}
        </div>

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