@extends('layouts.app')

@section('page-title', 'Annonces')

@section('sidebar')
    @include('hebergement.partials._sidebar')
@endsection

@section('content')
<div class="container-fluid p-0">

    <!-- ===== CAROUSEL ANNONCES URGENTES ===== -->
    @if($annoncesUrgentes->count() > 0)
    <div class="row justify-content-center" style="margin-top: 2rem; margin-bottom: 3rem; padding: 0 1rem;">
        <div class="col-lg-12">
            <div id="carouselUrgent" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
                <div class="carousel-inner">
                    @foreach($annoncesUrgentes as $index => $annonce)
                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
<div style="position: relative; height: 320px; background: url('{{ asset('photo/7.jpg') }}') center/cover no-repeat; border-radius: 12px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(to bottom, rgba(0,0,0,0.4) 0%, rgba(0,0,0,0.65) 100%);"></div>

    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; display: flex; flex-direction: column; justify-content: center; align-items: center; color: white; text-align: center; padding: 2rem;">

        <span style="background: rgba(239,68,68,0.95); padding: 0.5rem 1.2rem; border-radius: 50px; font-weight: bold; font-size: 0.9rem; margin-bottom: 1rem; display: inline-flex; align-items: center; gap: 0.4rem; box-shadow: 0 2px 8px rgba(239,68,68,0.5);">
            🔴 URGENT
        </span>

        <h2 style="font-size: 1.8rem; font-weight: bold; margin-bottom: 0.8rem; text-shadow: 2px 2px 8px rgba(0,0,0,0.8); line-height: 1.3;">
            {{ Str::limit($annonce->titre, 80) }}
        </h2>

        <p style="font-size: 1rem; text-shadow: 1px 1px 4px rgba(0,0,0,0.8); max-width: 600px; opacity: 0.9;">
            {{ Str::limit($annonce->contenu, 150, '...') }}
        </p>

        <div style="margin-top: 1.2rem;">
            <button class="btn btn-light btn-sm fw-bold px-4 py-2"
                    data-bs-toggle="modal"
                    data-bs-target="#annonce{{ $annonce->id }}"
                    style="border-radius: 50px; letter-spacing: 0.5px;">
                Voir plus →
            </button>
        </div>
    </div>
</div>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="annonce{{ $annonce->id }}" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title fw-bold">{{ $annonce->titre }}</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p>{{ $annonce->contenu }}</p>
                                    <hr>
                                    <small class="text-muted">
                                        <i class="bi bi-calendar"></i> {{ $annonce->created_at->format('d/m/Y H:i') }} -
                                        <i class="bi bi-person"></i> {{ $annonce->user->name ?? 'Admin' }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <button class="carousel-control-prev" type="button" data-bs-target="#carouselUrgent" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselUrgent" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>

                <div class="carousel-indicators">
                    @foreach($annoncesUrgentes as $index => $annonce)
                    <button type="button" data-bs-target="#carouselUrgent" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}"></button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- ===== CONTENU PRINCIPAL ===== -->
  <div class="container" style="margin-top: 2rem;">

    <!-- ===== BARRE RECHERCHE + TRI + FILTRE ===== -->
    <div class="row mb-4">
        <div class="col-md-12">
            <form method="GET" action="{{ route('hebergement.annonces.index') }}">
                <div class="card shadow-sm border-0 p-3">
                    <div class="row g-2 align-items-center">

                        <div class="col-md-5">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text"
                                       name="search"
                                       class="form-control border-start-0"
                                       placeholder="Rechercher une annonce..."
                                       value="{{ request('search') }}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <select name="tri" class="form-select">
                                <option value="recent" {{ request('tri', 'recent') == 'recent' ? 'selected' : '' }}>
                                     Plus récent
                                </option>
                                <option value="ancien" {{ request('tri') == 'ancien' ? 'selected' : '' }}>
                                     Plus ancien
                                </option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <select name="auteur" class="form-select">
                                <option value=""> Tous les auteurs</option>
                                <option value="admin" {{ request('auteur') == 'admin' ? 'selected' : '' }}>
                                    Administrateur
                                </option>
                                <option value="resp_foyer" {{ request('auteur') == 'resp_foyer' ? 'selected' : '' }}>
                                    Responsable Foyer
                                </option>
                            </select>
                        </div>

                        <div class="col-md-1">
                            <button type="submit" class="btn btn-primary w-100">
    Filtrer
</button>
                        </div>

                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- ===== TOUTES LES ANNONCES ===== -->
    <h5 class="fw-bold mb-3">
        <i class="bi bi-list-ul"></i> Toutes les annonces
        <span class="text-muted fs-6 fw-normal ms-2">({{ $annonces->total() }} résultats)</span>
    </h5>

    @if($annonces->count() > 0)
        <div class="row g-3">
            @foreach($annonces as $annonce)
@php
    $categories = [
        'generale'    => ['couleur' => '#6c757d', 'bg' => '#f8f9fa', 'label' => 'Générale'],
        'hebergement' => ['couleur' => '#0d6efd', 'bg' => '#e7f0ff', 'label' => 'Hébergement'],
        'foyer'       => ['couleur' => '#198754', 'bg' => '#e8f5ee', 'label' => 'Foyer'],
        'maintenance' => ['couleur' => '#fd7e14', 'bg' => '#fff3e0', 'label' => 'Maintenance'],
        'promotion'   => ['couleur' => '#dc3545', 'bg' => '#fde8ea', 'label' => 'Promotion'],
    ];
    $cat = $categories[$annonce->categorie] ?? $categories['generale'];

    $urgences = [
        'general'        => ['couleur' => '#0dcaf0', 'label' => 'Général'],
        'urgent'         => ['couleur' => '#dc3545', 'label' => 'Urgent'],
        'administration' => ['couleur' => '#212529', 'label' => 'Administration'],
    ];
    $urg = $urgences[$annonce->urgence ?? 'general'] ?? $urgences['general'];
@endphp

<div class="col-md-12">
    <div class="card border-0 shadow-sm hover-card"
         style="background-color: {{ $cat['bg'] }}; border-left: 5px solid {{ $cat['couleur'] }} !important; border-radius: 12px;">
        <div class="card-body py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="flex-grow-1">
                    <div class="mb-2">
                       <span class="badge rounded-pill" style="background-color: {{ $cat['couleur'] }};">{{ $cat['label'] }}</span>
@if($annonce->urgence !== 'general')
    <span class="badge rounded-pill ms-1" style="background-color: {{ $urg['couleur'] }};">{{ $urg['label'] }}</span>
@endif
                    </div>
                    <h5 class="fw-bold mb-1" style="color: {{ $cat['couleur'] }};">{{ $annonce->titre }}</h5>
                    <p class="text-muted mb-2" style="font-size: 0.9rem;">{{ Str::limit($annonce->contenu, 150, '...') }}</p>
                    <small class="text-muted">
                        {{ $annonce->created_at->format('d/m/Y') }} - {{ $annonce->user->name ?? 'Admin' }}
                    </small>
                </div>
                <button class="btn btn-sm ms-3 fw-bold"
                        style="background-color: {{ $cat['couleur'] }}; color: white; border-radius: 8px;"
                        data-bs-toggle="modal"
                        data-bs-target="#annonce{{ $annonce->id }}">
                    Voir
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="annonce{{ $annonce->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0" style="background-color: {{ $cat['bg'] }}; border-left: 5px solid {{ $cat['couleur'] }} !important;">
                <h5 class="modal-title fw-bold" style="color: {{ $cat['couleur'] }};">{{ $annonce->titre }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <span class="badge rounded-pill" style="background-color: {{ $cat['couleur'] }};">{{ $cat['label'] }}</span>
                    <span class="badge rounded-pill ms-1" style="background-color: {{ $urg['couleur'] }};">{{ $urg['label'] }}</span>
                </div>
                <p style="line-height: 1.8; font-size: 1.05rem;">{{ $annonce->contenu }}</p>
                <hr>
                <small class="text-muted">
                    {{ $annonce->created_at->format('d/m/Y à H:i') }} - {{ $annonce->user->name ?? 'Admin' }}
                </small>
            </div>
        </div>
    </div>
</div>
@endforeach
        </div>

        <!-- Pagination -->
        <div class="row mt-4">
            <div class="col-md-12">
                {{ $annonces->links() }}
            </div>
        </div>
    @else
        <div class="card text-center shadow-sm border-0">
            <div class="card-body py-5">
                <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                <p class="mt-3 text-muted">Aucune annonce trouvée</p>
            </div>
        </div>
    @endif
</div>
</div>

<style>
    .hover-card {
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .hover-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15) !important;
    }

    .carousel-item {
        transition: opacity 0.8s ease-in-out !important;
    }

    /* ── Mode nuit ── */
    [data-theme="dark"] .text-muted {
        color: var(--text-muted) !important;
    }

    [data-theme="dark"] .card,
    [data-theme="dark"] .modal-content,
    [data-theme="dark"] .modal-header {
        background-color: var(--bg-card) !important;
        color: var(--text-main);
        border-color: #444 !important;
    }

    [data-theme="dark"] .bg-white,
    [data-theme="dark"] .input-group-text.bg-white {
        background-color: #2d3139 !important;
        color: var(--text-main) !important;
        border-color: #444 !important;
    }

    [data-theme="dark"] .btn-close {
        filter: invert(1);
    }
</style>
@endsection