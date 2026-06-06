@extends('layouts.app')

@section('page-title', 'Annonces')

@section('sidebar')
    @include('etudiante.partials._sidebar')
@endsection

@section('content')
<div class="container-fluid p-0">

    <!-- ===== CAROUSEL ANNONCES URGENTES ===== -->
    @php
        $annoncesUrgentes = $annonces->where('urgence', 'urgent');
    @endphp

    @if($annoncesUrgentes->count() > 0)
    <div class="row justify-content-center" style="margin-top: 2rem; margin-bottom: 3rem; padding: 0 1rem;">
        <div class="col-lg-8">
            <div id="carouselUrgent" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
                <div class="carousel-inner">
                    @foreach($annoncesUrgentes as $index => $annonce)
                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                        <div style="position: relative; height: 320px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%), url('https://images.unsplash.com/photo-1552664730-d307ca884978?w=800&h=320&fit=crop') center/cover; border-radius: 12px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
                            
                            <!-- Overlay -->
                            <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.3);"></div>

                            <!-- Contenu -->
                            <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; display: flex; flex-direction: column; justify-content: center; align-items: center; color: white; text-align: center; padding: 2rem;">
                                
                                <!-- Badge -->
                                <span style="background: rgba(239,68,68,0.95); padding: 0.6rem 1.2rem; border-radius: 50px; font-weight: bold; font-size: 0.95rem; margin-bottom: 1rem; display: inline-block;">
                                    🔴 URGENT
                                </span>

                                <!-- Titre -->
                                <h2 style="font-size: 1.8rem; font-weight: bold; margin-bottom: 0.8rem; text-shadow: 2px 2px 6px rgba(0,0,0,0.5); line-height: 1.3;">
                                    {{ Str::limit($annonce->titre, 80) }}
                                </h2>

                                <!-- Description -->
                                <p style="font-size: 1rem; text-shadow: 1px 1px 4px rgba(0,0,0,0.5); max-width: 600px;">
                                    {{ Str::limit($annonce->contenu, 150, '...') }}
                                </p>

                                <!-- Bouton -->
                                <div style="margin-top: 1.2rem;">
                                    <button class="btn btn-light btn-sm fw-bold" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#annonce{{ $annonce->id }}">
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

                <!-- Navigation -->
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselUrgent" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselUrgent" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>

                <!-- Indicateurs -->
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
    <div class="container" style="margin-top: 3rem;">

        <!-- Barre de recherche -->
        <div class="row mb-4">
            <div class="col-md-12">
                <form method="GET" action="{{ route('etudiante.annonces') }}">
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" 
                               name="search" 
                               class="form-control border-start-0" 
                               placeholder="Rechercher une annonce..."
                               value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">Rechercher</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Filtres par auteur -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <label class="fw-bold mb-2">👤 Filtre par auteur</label>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('etudiante.annonces', ['search' => request('search')]) }}" 
                               class="btn {{ !request('auteur') ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">
                                ✓ Tous
                            </a>
                            <a href="{{ route('etudiante.annonces', ['auteur' => 'admin', 'search' => request('search')]) }}" 
                               class="btn {{ request('auteur') === 'admin' ? 'btn-danger' : 'btn-outline-danger' }} btn-sm">
                                👔 Administrateur
                            </a>
                            <a href="{{ route('etudiante.annonces', ['auteur' => 'resp_foyer', 'search' => request('search')]) }}" 
                               class="btn {{ request('auteur') === 'resp_foyer' ? 'btn-success' : 'btn-outline-success' }} btn-sm">
                                🏘️ Responsable Foyer
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ===== TOUTES LES ANNONCES ===== -->
        <div class="row mb-4">
            <div class="col-md-12">
                <h3 class="fw-bold mb-4">
                    <i class="bi bi-list-ul"></i> Toutes les annonces
                </h3>
            </div>
        </div>

        @if($annonces->count() > 0)
            <div class="row g-3">
                @foreach($annonces as $annonce)
                @php
                    $categories = [
                        'generale' => ['couleur' => 'secondary', 'label' => '📢 Générale'],
                        'hebergement' => ['couleur' => 'primary', 'label' => '🏠 Hébergement'],
                        'foyer' => ['couleur' => 'success', 'label' => '🛍️ Foyer'],
                        'maintenance' => ['couleur' => 'warning', 'label' => '🔧 Maintenance'],
                        'promotion' => ['couleur' => 'danger', 'label' => '🎉 Promotion'],
                    ];
                    $cat = $categories[$annonce->categorie] ?? $categories['generale'];
                    
                    $urgences = [
                        'general' => ['couleur' => 'info', 'label' => '📌 Général'],
                        'urgent' => ['couleur' => 'danger', 'label' => '🔴 Urgent'],
                        'administration' => ['couleur' => 'dark', 'label' => '👔 Administration'],
                    ];
                    $urg = $urgences[$annonce->urgence ?? 'general'] ?? $urgences['general'];
                @endphp
                <div class="col-md-12">
                    <div class="card border-0 shadow-sm hover-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <div class="mb-2">
                                        <span class="badge bg-{{ $cat['couleur'] }} me-2">{{ $cat['label'] }}</span>
                                        <span class="badge bg-{{ $urg['couleur'] }}">{{ $urg['label'] }}</span>
                                    </div>
                                    <h5 class="card-title fw-bold">{{ $annonce->titre }}</h5>
                                    <p class="card-text text-muted">{{ Str::limit($annonce->contenu, 150, '...') }}</p>
                                    <small class="text-muted">
                                        <i class="bi bi-calendar"></i> {{ $annonce->created_at->format('d/m/Y') }} - 
                                        <i class="bi bi-person"></i> {{ $annonce->user->name ?? 'Admin' }}
                                    </small>
                                </div>
                                <button class="btn btn-primary btn-sm ms-3" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#annonce{{ $annonce->id }}">
                                    Voir
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Détail -->
                <div class="modal fade" id="annonce{{ $annonce->id }}" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-light border-0">
                                <h5 class="modal-title fw-bold">{{ $annonce->titre }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <span class="badge bg-{{ $cat['couleur'] }} me-2">{{ $cat['label'] }}</span>
                                    <span class="badge bg-{{ $urg['couleur'] }}">{{ $urg['label'] }}</span>
                                </div>
                                <p style="line-height: 1.8; font-size: 1.05rem;">{{ $annonce->contenu }}</p>
                                <hr>
                                <div class="row">
                                    <div class="col-6">
                                        <small class="text-muted">
                                            <i class="bi bi-calendar"></i> 
                                            {{ $annonce->created_at->format('d/m/Y à H:i') }}
                                        </small>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted">
                                            <i class="bi bi-person"></i> 
                                            {{ $annonce->user->name ?? 'Admin' }}
                                        </small>
                                    </div>
                                </div>
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
</style>
@endsection