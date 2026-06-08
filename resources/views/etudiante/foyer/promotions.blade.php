@extends('layouts.app')
@section('title', 'Promotions')
@section('page-title', 'Promotions en cours')

@section('content')

{{-- Flèche retour --}}
<div class="mb-4">
    <a href="{{ route('etudiante.foyer.dashboard') }}"
       class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Retour au foyer
    </a>
</div>

@if($promotions->isEmpty())
    <div class="text-center text-muted py-5">
        <i class="bi bi-tag fs-1 d-block mb-3"></i>
        <h5>Aucune promotion en cours</h5>
        <p>Revenez plus tard pour voir les offres spéciales.</p>
        <a href="{{ route('etudiante.foyer.articles') }}" class="btn btn-primary">
            <i class="bi bi-shop me-1"></i>Voir le catalogue
        </a>
    </div>
@else
<div class="row g-4">
    @foreach($promotions as $promo)
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 border-0 shadow-sm"
             style="border-radius:16px; overflow:hidden;">

            {{-- Header coloré --}}
            <div style="background:linear-gradient(135deg,#dc3545,#e91e63); 
                        padding:20px; color:white;">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <i class="bi bi-tag-fill fs-3 mb-2 d-block"></i>
                        <h6 class="fw-bold mb-0">{{ $promo->titre }}</h6>
                    </div>
                    <span class="badge bg-white text-danger fw-bold">
                        Valide
                    </span>
                </div>
            </div>

            {{-- Corps --}}
            <div class="card-body">
                <p class="text-muted mb-3">{{ $promo->contenu }}</p>
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        <i class="bi bi-calendar me-1"></i>
                        Publié le {{ $promo->created_at->format('d/m/Y') }}
                    </small>
                    <small class="text-muted">
                        <i class="bi bi-clock me-1"></i>
                        {{ $promo->created_at->diffForHumans() }}
                    </small>
                </div>
            </div>

            {{-- Footer --}}
            <div class="card-footer bg-white border-0 pb-3">
                <a href="{{ route('etudiante.foyer.articles') }}"
                   class="btn btn-outline-danger btn-sm w-100">
                    <i class="bi bi-shop me-1"></i>Profiter de l'offre
                </a>
            </div>

        </div>
    </div>
    @endforeach
</div>
@endif

@endsection

@section('styles')
<style>
.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.12) !important;
    transition: all 0.3s;
}
</style>
@endsection