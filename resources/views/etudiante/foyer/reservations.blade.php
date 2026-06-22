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
   .stat-foyer {
        border-radius: 14px;
        padding: 20px;
        color: #fff;
        position: relative;
        overflow: hidden;
        cursor: pointer;
        transition: transform 0.15s, box-shadow 0.15s, opacity 0.15s;
    }
    .stat-foyer::after {
        content: '';
        position: absolute;
        right: -15px; bottom: -15px;
        width: 80px; height: 80px;
        border-radius: 50%;
        background: rgba(255,255,255,0.12);
    }
    .stat-foyer .big-num {
        font-size: 2.2rem;
        font-weight: 800;
        line-height: 1;
    }
    .stat-foyer .lbl {
        font-size: 0.82rem;
        opacity: 0.88;
        margin-top: 4px;
    }
    .stat-foyer i.bg-icon {
        position: absolute;
        right: 16px; top: 50%;
        transform: translateY(-50%);
        font-size: 2.8rem;
        opacity: 0.18;
    }
    .stat-foyer:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0,0,0,0.15);
    }
    .filter-card .stat-foyer {
        opacity: 0.55;
    }
    .filter-card.active .stat-foyer {
        opacity: 1;
        box-shadow: 0 6px 16px rgba(0,0,0,0.15);
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

@php
    $total      = $reservations->count();
    $enAttente  = $reservations->where('statut','en_attente')->count();
    $validees   = $reservations->where('statut','validee')->count();
    $refusees   = $reservations->where('statut','refusee')->count();
    $annulees   = $reservations->where('statut','annulee')->count();
@endphp

<div class="row g-3 mb-4">
    <div class="col-6 col-md filter-card active" data-filter="all">
        <div class="stat-foyer" style="background: linear-gradient(135deg,#1a3c5e,#2d6a9f);">
            <div class="big-num">{{ $total }}</div>
            <div class="lbl">Toutes</div>
            <i class="bi bi-cart-check bg-icon"></i>
        </div>
    </div>
    <div class="col-6 col-md filter-card" data-filter="en_attente">
        <div class="stat-foyer" style="background: linear-gradient(135deg,#b45309,#d97706);">
            <div class="big-num">{{ $enAttente }}</div>
            <div class="lbl">En attente</div>
            <i class="bi bi-clock bg-icon"></i>
        </div>
    </div>
    <div class="col-6 col-md filter-card" data-filter="validee">
        <div class="stat-foyer" style="background: linear-gradient(135deg,#0f6e40,#198754);">
            <div class="big-num">{{ $validees }}</div>
            <div class="lbl">Validées</div>
            <i class="bi bi-check-circle bg-icon"></i>
        </div>
    </div>
    <div class="col-6 col-md filter-card" data-filter="refusee">
        <div class="stat-foyer" style="background: linear-gradient(135deg,#842029,#dc3545);">
            <div class="big-num">{{ $refusees }}</div>
            <div class="lbl">Refusées</div>
            <i class="bi bi-x-circle bg-icon"></i>
        </div>
    </div>
    <div class="col-6 col-md filter-card" data-filter="annulee">
        <div class="stat-foyer" style="background: linear-gradient(135deg,#41464b,#6c757d);">
            <div class="big-num">{{ $annulees }}</div>
            <div class="lbl">Annulées</div>
            <i class="bi bi-slash-circle bg-icon"></i>
        </div>
    </div>
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
                                    <span class="fw-semibold">{{ number_format($resa->prix_unitaire_effectif ?? $resa->article->prix_actuel, 2) }} DA</span>
                                </div>
                                <div class="prix-total">
                                    Total : {{ number_format(($resa->prix_unitaire_effectif ?? $resa->article->prix_actuel) * $resa->quantite, 2) }} DA
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
        $panierTotal    = $panierArticles->sum(fn($r) => $r->prix_unitaire_effectif * $r->quantite);
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
                            <span style="color: #666; font-size: 0.9rem;">Qté: {{ $item->quantite }} × {{ number_format($item->prix_unitaire_effectif, 2) }} DA</span>
                        </div>
                        <div style="text-align: right;">
                            <strong style="color: #1a3c5e; font-size: 1.1rem;">{{ number_format($item->prix_unitaire_effectif * $item->quantite, 2) }} DA</strong>
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
    document.getElementById('formAnnuler').action = `/etudiante/foyer/annuler/${btn.dataset.id}`;
});

// ── Filtre par statut ──
document.querySelectorAll('.filter-card').forEach(card => {
    card.addEventListener('click', function() {
        document.querySelectorAll('.filter-card').forEach(c => c.classList.remove('active'));
        this.classList.add('active');
        const filter = this.dataset.filter;
        document.querySelectorAll('.resa-item').forEach(item => {
            item.style.display = (filter === 'all' || item.dataset.statut === filter) ? '' : 'none';
        });
    });
});
</script>
@endsection