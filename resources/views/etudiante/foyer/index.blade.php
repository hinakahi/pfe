@extends('layouts.app')
@section('title', 'Articles - ' . ucfirst($categorie))
@section('page-title', ucfirst($categorie))

@section('content')

{{-- Flèche retour --}}
<div class="mb-4">
    <a href="{{ route('etudiante.foyer.articles') }}"
       class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Retour aux catégories
    </a>
</div>

{{-- Barre de recherche --}}
<form method="GET" action="{{ route('etudiante.foyer.catalogue', $categorie) }}" class="mb-4">
    <div class="input-group" style="max-width:400px;">
        <span class="input-group-text bg-white">
            <i class="bi bi-search text-muted"></i>
        </span>
        <input type="text" name="search" value="{{ request('search') }}"
               class="form-control border-start-0"
               placeholder="Rechercher un article...">
        @if(request('search'))
        <a href="{{ route('etudiante.foyer.catalogue', $categorie) }}"
           class="btn btn-outline-secondary">
            <i class="bi bi-x"></i>
        </a>
        @endif
    </div>
</form>

{{-- Grille articles --}}
@if($articles->isEmpty())
    <div class="text-center text-muted py-5">
        <i class="bi bi-box-seam fs-1 d-block mb-3"></i>
        Aucun article disponible pour le moment.
    </div>
@else
<div class="row g-3">
    @foreach($articles as $article)
    <div class="col-md-3 col-sm-6">
        <div class="card h-100 position-relative" 
             style="border-radius:12px; overflow:hidden;">

            {{-- Badge PROMO --}}
            @if($article->promo_active && $article->prix_promo)
            <div style="position:absolute; top:8px; left:8px; z-index:2;">
                <span class="badge" 
                      style="background:linear-gradient(135deg,#dc3545,#e91e63); 
                             font-size:0.72rem; padding:4px 8px;">
                    <i class="bi bi-tag-fill me-1"></i>PROMO
                    @if($article->promo_date_fin)
                        — jusqu'au {{ \Carbon\Carbon::parse($article->promo_date_fin)->format('d/m') }}
                    @endif
                </span>
            </div>
            @endif

            {{-- Photo --}}
            <div style="height:130px; overflow:hidden;">
                @if($article->photo)
                    <img src="{{ asset('storage/' . $article->photo) }}"
                         style="width:100%; height:130px; object-fit:cover;">
                @else
                    <div style="height:130px; 
                                background:linear-gradient(135deg,#1a3c5e,#2d6a9f);
                                display:flex; align-items:center; justify-content:center;">
                        @if($categorie == 'fastfood')
                            <i class="bi bi-egg-fried text-white" style="font-size:3rem;"></i>
                        @elseif($categorie == 'magasin')
                            <i class="bi bi-bag text-white" style="font-size:3rem;"></i>
                        @else
                            <i class="bi bi-cup-hot text-white" style="font-size:3rem;"></i>
                        @endif
                    </div>
                @endif
            </div>

            <div class="card-body p-3 d-flex flex-column">

                {{-- Nom --}}
                <div class="fw-bold mb-1">{{ $article->nom_article }}</div>

                {{-- Description --}}
                @if($article->description)
                <div class="text-muted small mb-2">
                    {{ Str::limit($article->description, 50) }}
                </div>
                @endif

                {{-- Prix --}}
                <div class="mb-2">
                    @if($article->promo_active && $article->prix_promo)
                        <div class="text-decoration-line-through text-muted" 
                             style="font-size:0.8rem;">
                            {{ number_format($article->prix, 2) }} DA
                        </div>
                        <div class="fw-bold" style="color:#dc3545; font-size:1.1rem;">
                            {{ number_format($article->prix_promo, 2) }} DA
                        </div>
                    @else
                        <div class="fw-bold text-primary" style="font-size:1.1rem;">
                            {{ number_format($article->prix, 2) }} DA
                        </div>
                    @endif
                </div>

                {{-- Stock + Péremption --}}
                <div class="d-flex gap-1 flex-wrap mb-3">
                    <span class="badge {{ $article->stock <= 5 ? 'bg-warning text-dark' : 'bg-success' }}"
                          style="font-size:0.65rem;">
                        <i class="bi bi-box me-1"></i>Stock : {{ $article->stock }}
                    </span>
                    @if($article->date_peremption)
                    <span class="badge bg-light text-muted border" style="font-size:0.65rem;">
                        <i class="bi bi-calendar me-1"></i>
                        {{ \Carbon\Carbon::parse($article->date_peremption)->format('d/m/Y') }}
                    </span>
                    @endif
                </div>

                {{-- Bouton ajouter au panier --}}
                <form method="POST"
                      action="{{ route('etudiante.foyer.reserver', $article->id) }}"
                      class="mt-auto d-flex gap-1">
                    @csrf
                    <input type="number" name="quantite" value="1" min="1"
                           max="{{ $article->stock }}"
                           class="form-control form-control-sm"
                           style="width:60px;">
                    <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                        <i class="bi bi-cart-plus me-1"></i>Ajouter
                    </button>
                </form>

            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

{{-- Panier flottant --}}
@php
    $panierCount = $panier->count();
    $panierTotal = $panier->sum(function($r) {
        $prix = ($r->article->promo_active && $r->article->prix_promo)
                ? $r->article->prix_promo : $r->article->prix;
        return $r->quantite * $prix;
    });
@endphp

<div style="position:fixed; bottom:30px; right:30px; z-index:1000;">
    <button onclick="document.getElementById('panierModal').style.display='flex'"
            class="btn btn-primary rounded-circle shadow-lg"
            style="width:60px; height:60px; font-size:1.4rem; 
                   position:relative; display:flex; 
                   align-items:center; justify-content:center;">
        <i class="bi bi-cart3"></i>
        @if($panierCount > 0)
        <span style="position:absolute; top:-5px; right:-5px; background:#dc3545;
                     color:white; border-radius:50%; width:22px; height:22px;
                     font-size:0.7rem; display:flex; align-items:center; 
                     justify-content:center; font-weight:700;">
            {{ $panierCount }}
        </span>
        @endif
    </button>
</div>

{{-- Modal Panier --}}
<div id="panierModal"
     style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5);
            z-index:2000; align-items:center; justify-content:center;">
    <div style="background:white; width:440px; max-height:85vh; border-radius:20px;
                overflow:hidden; display:flex; flex-direction:column; 
                box-shadow:0 20px 60px rgba(0,0,0,0.3);">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center p-4"
             style="border-bottom:1px solid #f0f0f0;">
            <h6 class="mb-0 fw-bold fs-5">
                <i class="bi bi-cart3 me-2"></i>Mon panier
                @if($panierCount > 0)
                    <span class="badge bg-primary ms-1">{{ $panierCount }}</span>
                @endif
            </h6>
            <button onclick="document.getElementById('panierModal').style.display='none'"
                    style="background:none; border:none; font-size:1.4rem; 
                           cursor:pointer; color:#666;">✕</button>
        </div>

        {{-- Liste articles --}}
        <div style="overflow-y:auto; flex:1; padding:1rem 1.5rem;">
            @if($panier->isEmpty())
                <div class="text-center text-muted py-5">
                    <i class="bi bi-cart-x fs-1 d-block mb-2"></i>
                    Votre panier est vide.
                </div>
            @else
                @foreach($panier as $r)
                @php
                    $prix = ($r->article->promo_active && $r->article->prix_promo)
                            ? $r->article->prix_promo : $r->article->prix;
                    $sousTotal = $r->quantite * $prix;
                @endphp
                <div class="d-flex justify-content-between align-items-center py-3"
                     style="border-bottom:1px solid #f5f5f5;">
                    <div>
                        <div class="fw-semibold">{{ $r->article->nom_article }}</div>
                        <div class="text-muted small">
                            {{ number_format($prix, 0) }} DA × {{ $r->quantite }} =
                            <strong>{{ number_format($sousTotal, 0) }} DA</strong>
                        </div>
                    </div>
                    <form method="POST"
                          action="{{ route('etudiante.foyer.annuler', $r->id) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                style="background:none; border:none; 
                                       color:#dc3545; cursor:pointer; font-size:1.1rem;">
                            ✕
                        </button>
                    </form>
                </div>
                @endforeach

                {{-- Total --}}
                <div class="d-flex justify-content-between align-items-center pt-3">
                    <span class="fw-bold fs-6">Total</span>
                    <span class="fw-bold fs-5 text-primary">
                        {{ number_format($panierTotal, 0) }} DA
                    </span>
                </div>
            @endif
        </div>

        {{-- Bouton Commander --}}
        @if($panierCount > 0)
        <div style="border-top:1px solid #f0f0f0; padding:1rem 1.5rem;">
            <form method="POST" action="{{ route('etudiante.foyer.confirmer') }}">
                @csrf
                <button type="submit" class="btn w-100 text-white fw-semibold py-3"
                        style="background:linear-gradient(135deg,#198754,#20c997); 
                               border-radius:12px; font-size:1rem;">
                    <i class="bi bi-check2-circle me-2"></i>
                    Commander — {{ number_format($panierTotal, 0) }} DA
                </button>
            </form>
            <button onclick="document.getElementById('panierModal').style.display='none'"
                    class="btn btn-light w-100 mt-2"
                    style="border-radius:12px;">
                Continuer mes achats
            </button>
        </div>
        @endif

    </div>
</div>

@endsection

@section('styles')
<style>
.card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.12) !important;
    transition: all 0.3s;
}
</style>
@endsection