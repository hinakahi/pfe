@extends('layouts.app')

@section('title', 'Mon Foyer')
@section('page-title', 'Foyer')

@section('styles')
<style>
    .stat-foyer {
        border-radius: 14px;
        padding: 20px;
        color: #fff;
        position: relative;
        overflow: hidden;
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

    .promo-card {
        border-radius: 14px;
        overflow: hidden;
        border: none;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .promo-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.13);
    }
    .promo-img {
        width: 100%; height: 130px;
        object-fit: cover;
    }
    .promo-img-placeholder {
        width: 100%; height: 130px;
        background: linear-gradient(135deg,#fff3cd,#fde68a);
        display: flex; align-items: center; justify-content: center;
        font-size: 2.2rem; color: #b45309;
    }
    .promo-badge {
        position: absolute;
        top: 10px; left: 10px;
        background: #dc3545;
        color: #fff;
        font-size: 0.72rem;
        font-weight: 700;
        padding: 3px 9px;
        border-radius: 20px;
    }

    .statut-pill {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 20px;
        display: inline-block;
    }
    .statut-panier    { background:#cfe2ff; color:#084298; }
    .statut-en_attente{ background:#fff3cd; color:#856404; }
    .statut-validee   { background:#d1e7dd; color:#0a3622; }
    .statut-refusee   { background:#f8d7da; color:#58151c; }
    .statut-annulee   { background:#e2e3e5; color:#41464b; }

    .section-title {
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--text-main);
    }
    .section-title i { color: #2d6a9f; font-size: 1.1rem; }

    .resa-row {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 0;
        border-bottom: 1px solid rgba(0,0,0,0.06);
    }
    .resa-row:last-child { border-bottom: none; }
    .resa-thumb {
        width: 44px; height: 44px;
        border-radius: 8px; object-fit: cover; flex-shrink: 0;
    }
    .resa-thumb-ph {
        width: 44px; height: 44px;
        border-radius: 8px; flex-shrink: 0;
        background: linear-gradient(135deg,#e8f0fe,#d2e3fc);
        display: flex; align-items: center; justify-content: center;
        color: #4a90d9; font-size: 1.1rem;
    }
    .panier-banner {
        background: linear-gradient(135deg,#1a3c5e,#2d6a9f);
        border-radius: 14px;
        padding: 18px 22px;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 24px;
    }
    .panier-banner .nb {
        font-size: 1.8rem;
        font-weight: 800;
        line-height: 1;
    }
    .btn-confirmer {
        background: #fff;
        color: #1a3c5e;
        border: none;
        border-radius: 8px;
        padding: 8px 20px;
        font-weight: 700;
        font-size: 0.88rem;
        transition: opacity 0.2s;
        white-space: nowrap;
    }
    .btn-confirmer:hover { opacity: 0.85; }

    [data-theme="dark"] .resa-row { border-color: rgba(255,255,255,0.07); }
    [data-theme="dark"] .promo-img-placeholder { background: linear-gradient(135deg,#2d2000,#3d2e00); color:#fbbf24; }
</style>
@endsection

@section('content')

@php
    $panierCount = $reservations->where('statut','panier')->count();
    $panierTotal = $reservations->where('statut','panier')->sum(fn($r) => $r->article ? $r->article->prix * $r->quantite : 0);
@endphp

{{-- Stats cliquables --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4">
        <a href="{{ route('etudiante.foyer.reservations') }}" style="text-decoration:none;">
            <div class="stat-foyer" style="background: linear-gradient(135deg,#1a3c5e,#2d6a9f); cursor:pointer;">
                <div class="big-num">{{ $totalReservations }}</div>
                <div class="lbl">Mes réservations</div>
                <i class="bi bi-cart-check bg-icon"></i>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4">
        <a href="{{ route('etudiante.foyer.catalogue', 'tous') }}" style="text-decoration:none;">
            <div class="stat-foyer" style="background: linear-gradient(135deg,#0f6e40,#198754); cursor:pointer;">
                <div class="big-num">{{ $totalArticles }}</div>
                <div class="lbl">Articles disponibles</div>
                <i class="bi bi-box-seam bg-icon"></i>
            </div>
        </a>
    </div>
    <div class="col-12 col-md-4">
        <a href="{{ route('etudiante.foyer.promotions') }}" style="text-decoration:none;">
            <div class="stat-foyer" style="background: linear-gradient(135deg,#b45309,#d97706); cursor:pointer;">
                <div class="big-num">{{ $totalPromotions }}</div>
                <div class="lbl">Promotions en cours</div>
                <i class="bi bi-tag bg-icon"></i>
            </div>
        </a>
    </div>
</div>

<div class="row g-4">

    {{-- Promotions en cours --}}
    @if($promotions->isNotEmpty())
    <div class="col-12 col-lg-5">
        <div class="section-title">
            <i class="bi bi-tag-fill"></i> Promotions en cours
        </div>
        <div class="row g-2">
            @foreach($promotions->take(4) as $promo)
            <div class="col-6">
                <div class="card promo-card">
                    <div style="position:relative;">
                        @if($promo->photo)
                            <img src="{{ asset('storage/'.$promo->photo) }}"
                                 alt="{{ $promo->nom_article }}"
                                 class="promo-img">
                        @else
                            <div class="promo-img-placeholder">
                                <i class="bi bi-tag"></i>
                            </div>
                        @endif
                        <span class="promo-badge">PROMO</span>
                    </div>
                    <div class="card-body p-2">
                        <div class="fw-semibold" style="font-size:.82rem; line-height:1.3;">
                            {{ Str::limit($promo->nom_article, 28) }}
                        </div>
                        <div class="fw-bold mt-1" style="font-size:.9rem; color:#1a3c5e;">
                            {{ number_format($promo->prix_promo ?? $promo->prix, 2) }} DA
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Réservations récentes --}}
    <div class="col-12 col-lg-{{ $promotions->isNotEmpty() ? '7' : '12' }}">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="section-title mb-0">
                <i class="bi bi-clock-history"></i> Réservations récentes
            </div>
            <a href="{{ route('etudiante.foyer.reservations') }}"
               class="btn btn-sm btn-outline-primary" style="font-size:.8rem;">
                Voir tout
            </a>
        </div>

        {{-- Filtre statut --}}
        <form method="GET" action="{{ route('etudiante.foyer.dashboard') }}"
              class="d-flex gap-2 mb-3 flex-wrap">
            <select name="statut" class="form-select form-select-sm" style="width:auto;"
                    onchange="this.form.submit()">
                <option value="tous" {{ request('statut','tous')==='tous'?'selected':'' }}>Tous les statuts</option>
                <option value="panier"     {{ request('statut')==='panier'    ?'selected':'' }}>Panier</option>
                <option value="en_attente" {{ request('statut')==='en_attente'?'selected':'' }}>En attente</option>
                <option value="validee"    {{ request('statut')==='validee'   ?'selected':'' }}>Validée</option>
                <option value="refusee"    {{ request('statut')==='refusee'   ?'selected':'' }}>Refusée</option>
            </select>
            <input type="text" name="search" class="form-control form-control-sm"
                   placeholder="Rechercher..." value="{{ request('search') }}"
                   style="max-width:180px;">
            <button type="submit" class="btn btn-sm btn-primary">
                <i class="bi bi-search"></i>
            </button>
        </form>

        <div class="card p-3">
            @forelse($reservations->take(6) as $resa)
                @if($resa->article)
                <div class="resa-row">
                    {{-- Image --}}
                    @if($resa->article->photo)
                        <img src="{{ asset('storage/'.$resa->article->photo) }}"
                             alt="{{ $resa->article->nom_article }}"
                             class="resa-thumb">
                    @else
                        <div class="resa-thumb-ph">
                            <i class="bi bi-box-seam"></i>
                        </div>
                    @endif

                    {{-- Info --}}
                    <div class="flex-grow-1 min-width-0">
                        <div class="fw-semibold" style="font-size:.88rem;">
                            {{ Str::limit($resa->article->nom_article, 32) }}
                        </div>
                        <div class="text-muted" style="font-size:.78rem;">
                            Qté : {{ $resa->quantite }}
                            &nbsp;•&nbsp;
                            {{ number_format($resa->article->prix * $resa->quantite, 2) }} DA
                        </div>
                    </div>

                    {{-- Statut --}}
                    <span class="statut-pill statut-{{ $resa->statut }}">
                        @switch($resa->statut)
                            @case('panier')     <i class="bi bi-cart me-1"></i>Panier @break
                            @case('en_attente') <i class="bi bi-clock me-1"></i>En attente @break
                            @case('validee')    <i class="bi bi-check-circle me-1"></i>Validée @break
                            @case('refusee')    <i class="bi bi-x-circle me-1"></i>Refusée @break
                            @case('annulee')    <i class="bi bi-slash-circle me-1"></i>Annulée @break
                        @endswitch
                    </span>

                    {{-- Annuler --}}
                    @if(in_array($resa->statut, ['panier','en_attente']))
                    <form method="POST"
                          action="{{ route('etudiante.foyer.annuler', $resa->id) }}"
                          onsubmit="return confirm('Annuler cette réservation ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm p-1 px-2"
                                title="Annuler">
                            <i class="bi bi-x-lg" style="font-size:.75rem;"></i>
                        </button>
                    </form>
                    @endif
                </div>
                @endif
            @empty
            <div class="text-center py-4 text-muted">
                <i class="bi bi-cart-x" style="font-size:2rem; display:block; margin-bottom:8px;"></i>
                <span class="small">Aucune réservation trouvée</span><br>
                <a href="{{ route('etudiante.foyer.catalogue', 'tous') }}"
                   class="btn btn-sm btn-primary mt-2">
                    <i class="bi bi-shop me-1"></i>Voir le catalogue
                </a>
            </div>
            @endforelse
        </div>
    </div>

</div>
@endsection