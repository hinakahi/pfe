@extends('layouts.app')
@section('title', 'Liste des Articles')
@section('page-title', 'Liste des Articles')

@section('content')

{{-- Flèche retour --}}
<div class="mb-4">
    <a href="{{ route('etudiante.foyer.dashboard') }}" 
       class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Retour au foyer
    </a>
</div>

{{-- Panier flottant badge --}}
@if($panierCount > 0)
<div style="position:fixed; bottom:30px; right:30px; z-index:1000;">
    <a href="{{ route('etudiante.foyer.reservations') }}" 
       class="btn btn-primary rounded-circle shadow-lg"
       style="width:60px; height:60px; font-size:1.4rem; position:relative; display:flex; align-items:center; justify-content:center;">
        <i class="bi bi-cart3"></i>
        <span style="position:absolute; top:-5px; right:-5px; background:#dc3545;
                     color:white; border-radius:50%; width:22px; height:22px;
                     font-size:0.7rem; display:flex; align-items:center; 
                     justify-content:center; font-weight:700;">
            {{ $panierCount }}
        </span>
    </a>
</div>
@endif

{{-- Titre --}}
<div class="text-center mb-5">
    <h4 class="fw-bold">Choisissez une catégorie</h4>
    <p class="text-muted">Sélectionnez la catégorie d'articles qui vous intéresse</p>
</div>

{{-- 3 Cartes catégories --}}
<div class="row g-4 justify-content-center">

    {{-- FastFood --}}
    <div class="col-md-4">
        <a href="{{ route('etudiante.foyer.catalogue', 'fastfood') }}" 
           class="text-decoration-none">
            <div class="card h-100 shadow-sm text-center"
                 style="border-radius:20px; overflow:hidden; border:none; 
                        transition: all 0.3s; cursor:pointer;">
                <div style="background: linear-gradient(135deg, #ff6b35, #f7c59f); 
                            padding:40px 20px; color:white;">
                    <i class="bi bi-egg-fried" style="font-size:4rem; display:block; margin-bottom:15px;"></i>
                    <h4 class="fw-bold mb-1">Fast Food</h4>
                    <p class="mb-0" style="opacity:0.9;">Burgers, pizzas, sandwichs...</p>
                </div>
                <div class="card-body">
                    <div class="fw-bold text-warning" style="font-size:1.3rem;">
                        {{ $fastfood }} articles
                    </div>
                    <small class="text-muted">
                        <i class="bi bi-arrow-right me-1"></i>Voir les articles
                    </small>
                </div>
            </div>
        </a>
    </div>

    {{-- Cafétéria --}}
    <div class="col-md-4">
        <a href="{{ route('etudiante.foyer.catalogue', 'cafeteria') }}" 
           class="text-decoration-none">
            <div class="card h-100 shadow-sm text-center"
                 style="border-radius:20px; overflow:hidden; border:none; 
                        transition: all 0.3s; cursor:pointer;">
                <div style="background: linear-gradient(135deg, #1a3c5e, #2d6a9f); 
                            padding:40px 20px; color:white;">
                    <i class="bi bi-cup-hot" style="font-size:4rem; display:block; margin-bottom:15px;"></i>
                    <h4 class="fw-bold mb-1">Cafétéria</h4>
                    <p class="mb-0" style="opacity:0.9;">Café, jus, viennoiseries...</p>
                </div>
                <div class="card-body">
                    <div class="fw-bold text-primary" style="font-size:1.3rem;">
                        {{ $cafeteria }} articles
                    </div>
                    <small class="text-muted">
                        <i class="bi bi-arrow-right me-1"></i>Voir les articles
                    </small>
                </div>
            </div>
        </a>
    </div>

    {{-- Magasin --}}
    <div class="col-md-4">
        <a href="{{ route('etudiante.foyer.catalogue', 'magasin') }}" 
           class="text-decoration-none">
            <div class="card h-100 shadow-sm text-center"
                 style="border-radius:20px; overflow:hidden; border:none; 
                        transition: all 0.3s; cursor:pointer;">
                <div style="background: linear-gradient(135deg, #198754, #20c997); 
                            padding:40px 20px; color:white;">
                    <i class="bi bi-bag" style="font-size:4rem; display:block; margin-bottom:15px;"></i>
                    <h4 class="fw-bold mb-1">Magasin</h4>
                    <p class="mb-0" style="opacity:0.9;">Produits, snacks, boissons...</p>
                </div>
                <div class="card-body">
                    <div class="fw-bold text-success" style="font-size:1.3rem;">
                        {{ $magasin }} articles
                    </div>
                    <small class="text-muted">
                        <i class="bi bi-arrow-right me-1"></i>Voir les articles
                    </small>
                </div>
            </div>
        </a>
    </div>

</div>

@endsection

@section('styles')
<style>
.card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.15) !important;
}
</style>
@endsection