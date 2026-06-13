@extends('layouts.app')

@section('title', 'Mes Réservations - Foyer')
@section('page-title', 'Foyer')

@section('styles')
<style>
    .resa-card {
        border-radius: 14px;
        overflow: hidden;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .resa-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.12);
    }
    .statut-pill {
        font-size: 0.78rem;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 20px;
    }
    .statut-en_attente  { background: #fff3cd; color: #856404; }
    .statut-validee     { background: #d1e7dd; color: #0a3622; }
    .statut-refusee     { background: #f8d7da; color: #58151c; }
    .statut-annulee     { background: #e2e3e5; color: #41464b; }
    .article-thumb {
        width: 56px; height: 56px;
        border-radius: 10px;
        object-fit: cover;
        background: #e8f0fe;
        flex-shrink: 0;
    }
    .article-thumb-placeholder {
        width: 56px; height: 56px;
        border-radius: 10px;
        background: linear-gradient(135deg,#e8f0fe,#d2e3fc);
        display: flex; align-items: center; justify-content: center;
        color: #4a90d9; font-size: 1.4rem;
        flex-shrink: 0;
    }
    .filter-tabs {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }
    .filter-tabs button {
        border: none;
        border-radius: 20px;
        padding: 5px 16px;
        font-size: 0.85rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        background: var(--bg-card);
        color: var(--text-muted);
        box-shadow: var(--shadow);
    }
    .filter-tabs button.active {
        background: linear-gradient(135deg,#1a3c5e,#2d6a9f);
        color: #fff;
    }
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: var(--text-muted);
    }
    .empty-state i { font-size: 3rem; margin-bottom: 12px; display: block; }
    .prix-total {
        font-size: 0.95rem;
        font-weight: 700;
        color: #1a3c5e;
    }
    [data-theme="dark"] .prix-total { color: #7eb3e8; }
</style>
@endsection

@section('content')

{{-- En-tête --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">
            <i class="bi bi-cart-check me-2" style="color:#2d6a9f;"></i>Mes Réservations
        </h4>
        <p class="text-muted small mb-0">Suivi de toutes vos réservations du foyer</p>
    </div>
    <a href="{{ route('etudiante.foyer.dashboard') }}" class="btn btn-outline-primary btn-sm">
        <i class="bi bi-shop me-1"></i>Retour au catalogue
    </a>
</div>

{{-- Statistiques rapides --}}
<div class="row g-3 mb-4">
    @php
        $total      = $reservations->count();
        $enAttente  = $reservations->where('statut','en_attente')->count();
        $validees   = $reservations->where('statut','validee')->count();
        $refusees   = $reservations->where('statut','refusee')->count();
    @endphp
    <div class="col-6 col-md-3">
        <div class="card text-center p-3">
            <div style="font-size:1.6rem; font-weight:700; color:#1a3c5e;">{{ $total }}</div>
            <div class="small text-muted">Total</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center p-3">
            <div style="font-size:1.6rem; font-weight:700; color:#856404;">{{ $enAttente }}</div>
            <div class="small text-muted">En attente</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center p-3">
            <div style="font-size:1.6rem; font-weight:700; color:#0a3622;">{{ $validees }}</div>
            <div class="small text-muted">Validées</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center p-3">
            <div style="font-size:1.6rem; font-weight:700; color:#58151c;">{{ $refusees }}</div>
            <div class="small text-muted">Refusées</div>
        </div>
    </div>
</div>

{{-- Onglets filtre --}}
<div class="filter-tabs">
    <button class="active" data-filter="all">Toutes ({{ $total }})</button>
    <button data-filter="en_attente">
        <i class="bi bi-clock me-1"></i>En attente ({{ $enAttente }})
    </button>
    <button data-filter="validee">
        <i class="bi bi-check-circle me-1"></i>Validées ({{ $validees }})
    </button>
    <button data-filter="refusee">
        <i class="bi bi-x-circle me-1"></i>Refusées ({{ $refusees }})
    </button>
    <button data-filter="annulee">
        <i class="bi bi-slash-circle me-1"></i>Annulées
    </button>
</div>

{{-- Liste des réservations --}}
@if($reservations->isEmpty())
    <div class="empty-state">
        <i class="bi bi-cart-x"></i>
        <h6>Aucune réservation</h6>
        <p class="small">Vous n'avez pas encore fait de réservation.</p>
        <a href="{{ route('etudiante.foyer.dashboard') }}" class="btn btn-primary btn-sm mt-2">
            <i class="bi bi-shop me-1"></i>Voir le catalogue
        </a>
    </div>
@else
    <div id="reservationsList">
        @foreach($reservations as $resa)
            @if($resa->article)
            <div class="card resa-card mb-3 resa-item" data-statut="{{ $resa->statut }}">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center gap-3">

                        {{-- Image article --}}
                        @if($resa->article->photo)
                            <img src="{{ asset('storage/' . $resa->article->photo) }}"
                                 alt="{{ $resa->article->nom_article }}"
                                 class="article-thumb">
                        @else
                            <div class="article-thumb-placeholder">
                                <i class="bi bi-box-seam"></i>
                            </div>
                        @endif

                        {{-- Info --}}
                        <div class="flex-grow-1 min-width-0">
                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                <div>
                                    <h6 class="fw-semibold mb-0">{{ $resa->article->nom_article }}</h6>
                                    <div class="small text-muted mt-1">
                                        <i class="bi bi-calendar3 me-1"></i>
                                        {{ $resa->date_reservation->format('d/m/Y à H:i') }}
                                    </div>
                                </div>
                                <span class="statut-pill statut-{{ $resa->statut }}">
                                    @switch($resa->statut)
                                        @case('en_attente') <i class="bi bi-clock me-1"></i>En attente @break
                                        @case('validee')    <i class="bi bi-check-circle me-1"></i>Validée @break
                                        @case('refusee')    <i class="bi bi-x-circle me-1"></i>Refusée @break
                                        @case('annulee')    <i class="bi bi-slash-circle me-1"></i>Annulée @break
                                    @endswitch
                                </span>
                            </div>

                            <div class="d-flex align-items-center justify-content-between mt-2 flex-wrap gap-2">
                                <div class="small text-muted">
                                    Quantité :
                                    <span class="fw-semibold">{{ $resa->quantite }}</span>
                                    &nbsp;•&nbsp;
                                    Prix unitaire :
                                    <span class="fw-semibold">{{ number_format($resa->article->prix, 2) }} DA</span>
                                </div>
                                <div class="prix-total">
                                    Total : {{ number_format($resa->article->prix * $resa->quantite, 2) }} DA
                                </div>
                            </div>
                        </div>

                        {{-- Bouton annuler --}}
                        @if($resa->statut === 'en_attente')
                        <div class="ms-2">
                            <button type="button"
                                    class="btn btn-outline-danger btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalAnnuler"
                                    data-id="{{ $resa->id }}"
                                    data-nom="{{ $resa->article->nom_article }}"
                                    title="Annuler">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                        @endif

                    </div>
                </div>
            </div>
            @endif
        @endforeach
    </div>
@endif

{{-- ===== MODAL ANNULER ===== --}}
<div class="modal fade" id="modalAnnuler" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content" style="border-radius:14px; border:none;">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold">
                    <i class="bi bi-exclamation-triangle me-2 text-warning"></i>Annuler la réservation
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-1">
                <p class="small text-muted mb-0">
                    Voulez-vous annuler la réservation de
                    <strong id="modalNomAnnuler"></strong> ?
                </p>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Non</button>
                <form id="formAnnuler" method="POST" action="" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="bi bi-x-circle me-1"></i>Oui, annuler
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@if(auth()->check())
    @php
        $panierCount    = \App\Models\Reservation::where('etudiante_id', auth()->id())
                            ->where('statut', 'panier')->count();
        $panierArticles = \App\Models\Reservation::where('etudiante_id', auth()->id())
                            ->where('statut', 'panier')->with('article')->get()
                            ->filter(fn($r) => $r->article);
        $panierTotal    = $panierArticles->sum(fn($r) => $r->article->prix * $r->quantite);
    @endphp

    @if($panierCount > 0)
    <!-- Bouton panier sticky -->
    <button type="button"
            style="position: fixed; bottom: 30px; right: 30px; width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg,#1a3c5e,#2d6a9f); color: #fff; border: none; cursor: pointer; box-shadow: 0 4px 12px rgba(0,0,0,0.3); z-index: 999; display: flex; align-items: center; justify-content: center; font-size: 1.8rem;"
            data-bs-toggle="modal" data-bs-target="#modalPanier">
        <i class="bi bi-cart3"></i>
        <span style="position: absolute; top: -8px; right: -8px; background: #dc3545; color: #fff; border-radius: 50%; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: bold;">{{ $panierCount }}</span>
    </button>

    <!-- Modal Panier -->
    <div class="modal fade" id="modalPanier" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-cart3 me-2"></i>Mon Panier
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @forelse($panierArticles as $item)
                    <div style="display: flex; align-items: center; gap: 12px; padding: 12px 0; border-bottom: 1px solid #dee2e6;">
                        @if($item->article->photo)
                            <img src="{{ asset('storage/' . $item->article->photo) }}"
                                 alt="{{ $item->article->nom_article }}"
                                 style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                        @else
                            <div style="width: 60px; height: 60px; background: linear-gradient(135deg,#e8f0fe,#d2e3fc); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-box-seam" style="color: #4a90d9;"></i>
                            </div>
                        @endif
                        <div style="flex: 1;">
                            <strong>{{ $item->article->nom_article }}</strong><br>
                            <span style="color: #666; font-size: 0.9rem;">Qté: {{ $item->quantite }} × {{ number_format($item->article->prix, 2) }} DA</span>
                        </div>
                        <div style="text-align: right;">
                            <strong style="color: #1a3c5e; font-size: 1.1rem;">{{ number_format($item->article->prix * $item->quantite, 2) }} DA</strong>
                        </div>
                        <form method="POST" action="{{ route('etudiante.foyer.annuler', $item->id) }}" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Supprimer" onclick="return confirm('Supprimer cet article ?')">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </form>
                    </div>
                    @empty
                    <p class="text-center text-muted">Panier vide</p>
                    @endforelse
                </div>
                <div class="modal-footer" style="border-top: 2px solid #dee2e6;">
                    <div style="flex: 1; text-align: left;">
                        <strong style="font-size: 1.2rem; color: #1a3c5e;">Total : {{ number_format($panierTotal, 2) }} DA</strong>
                    </div>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <form method="POST" action="{{ route('etudiante.foyer.confirmer') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>Confirmer la commande
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
@endif

@endsection

@section('scripts')
<script>
// ── Modal annuler ──
document.getElementById('modalAnnuler').addEventListener('show.bs.modal', e => {
    const btn = e.relatedTarget;
    document.getElementById('modalNomAnnuler').textContent = btn.dataset.nom;
    document.getElementById('formAnnuler').action = `/etudiante/foyer/reservations/${btn.dataset.id}`;
});

// ── Filtre par statut ──
document.querySelectorAll('.filter-tabs button').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.filter-tabs button').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const filter = this.dataset.filter;
        document.querySelectorAll('.resa-item').forEach(item => {
            item.style.display = (filter === 'all' || item.dataset.statut === filter) ? '' : 'none';
        });
    });
});
</script>
@endsection