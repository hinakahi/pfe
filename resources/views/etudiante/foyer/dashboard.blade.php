@extends('layouts.app')
@section('title', 'Foyer')
@section('page-title', 'Foyer')

@section('content')

{{-- STATS EN HAUT --}}
<div class="row mb-5">
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg,#1a3c5e,#2d6a9f)">
            <div class="number">{{ $totalReservations }}</div>
            <div class="label"><i class="bi bi-list-check me-1"></i>Total réservations</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg,#fd7e14,#ffc107)">
            <div class="number">{{ $enAttente }}</div>
            <div class="label"><i class="bi bi-hourglass-split me-1"></i>En attente</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg,#198754,#20c997)">
            <div class="number">{{ $validees }}</div>
            <div class="label"><i class="bi bi-check-circle me-1"></i>Validées</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg,#dc3545,#e91e63)">
            <div class="number">{{ $annulees }}</div>
            <div class="label"><i class="bi bi-x-circle me-1"></i>Annulées</div>
        </div>
    </div>
</div>

{{-- 3 CARTES CLIQUABLES --}}
<div class="row g-4">
    
    {{-- CARTE 1 : CATALOGUE --}}
    <div class="col-md-4">
        <a href="{{ route('etudiante.foyer') }}" class="text-decoration-none">
            <div class="card h-100 shadow-sm" style="border-radius: 16px; overflow: hidden; cursor: pointer; transition: all 0.3s; border: none;">
                <div style="background: linear-gradient(135deg, #38b6ff, #1a8fd1); padding: 30px; text-align: center; color: white;">
                    <i class="bi bi-shop" style="font-size: 3rem; display: block; margin-bottom: 15px;"></i>
                    <h5 class="fw-bold mb-2">Catalogue</h5>
                    <p class="mb-0" style="opacity: 0.9;">Voir les articles disponibles</p>
                </div>
                <div class="card-body text-center">
                    <div class="fw-bold text-primary" style="font-size: 1.5rem;">
                        {{ $totalArticles }} articles
                    </div>
                    <small class="text-muted d-block mt-2">
                        <i class="bi bi-arrow-right me-1"></i>Consulter
                    </small>
                </div>
            </div>
        </a>
    </div>

    {{-- CARTE 2 : MES RÉSERVATIONS --}}
    <div class="col-md-4">
        <a href="{{ route('etudiante.foyer.reservations') }}" class="text-decoration-none">
            <div class="card h-100 shadow-sm" style="border-radius: 16px; overflow: hidden; cursor: pointer; transition: all 0.3s; border: none;">
                <div style="background: linear-gradient(135deg, #7c3aed, #a855f7); padding: 30px; text-align: center; color: white;">
                    <i class="bi bi-clipboard-check" style="font-size: 3rem; display: block; margin-bottom: 15px;"></i>
                    <h5 class="fw-bold mb-2">Mes Réservations</h5>
                    <p class="mb-0" style="opacity: 0.9;">Voir mon historique</p>
                </div>
                <div class="card-body text-center">
                    <div class="fw-bold" style="color: #7c3aed; font-size: 1.5rem;">
                        {{ $totalReservations }} réservations
                    </div>
                    <small class="text-muted d-block mt-2">
                        <i class="bi bi-arrow-right me-1"></i>Consulter
                    </small>
                </div>
            </div>
        </a>
    </div>

    {{-- CARTE 3 : PROMOTIONS --}}
    <div class="col-md-4">
        <a href="{{ route('etudiante.foyer') }}#promotions" class="text-decoration-none">
            <div class="card h-100 shadow-sm" style="border-radius: 16px; overflow: hidden; cursor: pointer; transition: all 0.3s; border: none;">
                <div style="background: linear-gradient(135deg, #dc3545, #e91e63); padding: 30px; text-align: center; color: white;">
                    <i class="bi bi-tag-fill" style="font-size: 3rem; display: block; margin-bottom: 15px;"></i>
                    <h5 class="fw-bold mb-2">Promotions</h5>
                    <p class="mb-0" style="opacity: 0.9;">Voir les offres spéciales</p>
                </div>
                <div class="card-body text-center">
                    <div class="fw-bold" style="color: #dc3545; font-size: 1.5rem;">
                        {{ $totalPromotions }} promotion{{ $totalPromotions != 1 ? 's' : '' }}
                    </div>
                    <small class="text-muted d-block mt-2">
                        <i class="bi bi-arrow-right me-1"></i>Consulter
                    </small>
                </div>
            </div>
        </a>
    </div>

</div>

{{-- PROMOTIONS RÉCENTES EN BAS --}}
@if($promotions->count() > 0)
<div class="mt-5">
    <h5 class="mb-4">
        <i class="bi bi-fire text-danger me-2"></i>Promotions en cours
    </h5>
    <div class="row g-3">
        @foreach($promotions as $promo)
        <div class="col-md-6 col-lg-4">
            <div class="card border-danger h-100" style="border-left: 4px solid #dc3545; border-radius: 12px;">
                <div class="card-body">
                    <h6 class="card-title text-danger fw-bold">
                        <i class="bi bi-tag-fill me-1"></i>{{ $promo->titre }}
                    </h6>
                    <p class="card-text small text-muted">{{ Str::limit($promo->contenu, 80) }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="bi bi-calendar me-1"></i>{{ $promo->created_at->format('d/m/Y') }}
                        </small>
                        <span class="badge bg-danger">Valide</span>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

@endsection

@section('styles')
<style>
.stat-card {
    border-radius: 12px;
    padding: 20px;
    color: white;
    margin-bottom: 20px;
}

.stat-card .number {
    font-size: 2rem;
    font-weight: 700;
}

.stat-card .label {
    font-size: 0.85rem;
    opacity: 0.85;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15) !important;
}
</style>
@endsection