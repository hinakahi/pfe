@extends('layouts.app')

@section('page-title', 'Annonces')

@section('sidebar')
    @include('etudiante.partials._sidebar')
@endsection

@section('content')
<div class="container-fluid">
    <!-- Titre -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h2><i class="bi bi-megaphone"></i> Annonces</h2>
            <p class="text-muted">Consultez les dernières annonces de la résidence</p>
        </div>
    </div>

    <!-- ===== FILTRES EN HAUT ===== -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <!-- Barre de recherche -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <form method="GET" action="{{ route('etudiante.annonces') }}">
                                <div class="input-group">
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

                    <!-- Filtres Catégorie -->
                    <div class="mb-4">
                        <label class="fw-bold mb-2">📂 Catégorie</label>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('etudiante.annonces', ['categorie' => '', 'urgence' => request('urgence')]) }}" 
                               class="btn {{ !request('categorie') ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">
                                ✓ Toutes
                            </a>
                            <a href="{{ route('etudiante.annonces', ['categorie' => 'generale', 'urgence' => request('urgence')]) }}" 
                               class="btn {{ request('categorie') === 'generale' ? 'btn-secondary' : 'btn-outline-secondary' }} btn-sm">
                                📢 Générale
                            </a>
                            <a href="{{ route('etudiante.annonces', ['categorie' => 'hebergement', 'urgence' => request('urgence')]) }}" 
                               class="btn {{ request('categorie') === 'hebergement' ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">
                                🏠 Hébergement
                            </a>
                            <a href="{{ route('etudiante.annonces', ['categorie' => 'foyer', 'urgence' => request('urgence')]) }}" 
                               class="btn {{ request('categorie') === 'foyer' ? 'btn-success' : 'btn-outline-success' }} btn-sm">
                                🛍️ Foyer
                            </a>
                            <a href="{{ route('etudiante.annonces', ['categorie' => 'maintenance', 'urgence' => request('urgence')]) }}" 
                               class="btn {{ request('categorie') === 'maintenance' ? 'btn-warning' : 'btn-outline-warning' }} btn-sm">
                                🔧 Maintenance
                            </a>
                            <a href="{{ route('etudiante.annonces', ['categorie' => 'promotion', 'urgence' => request('urgence')]) }}" 
                               class="btn {{ request('categorie') === 'promotion' ? 'btn-danger' : 'btn-outline-danger' }} btn-sm">
                                🎉 Promotion
                            </a>
                        </div>
                    </div>

                    <!-- Filtres Urgence -->
                    <div>
                        <label class="fw-bold mb-2">⚡ Urgence</label>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('etudiante.annonces', ['urgence' => '', 'categorie' => request('categorie')]) }}" 
                               class="btn {{ !request('urgence') ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">
                                ✓ Toutes
                            </a>
                            <a href="{{ route('etudiante.annonces', ['urgence' => 'general', 'categorie' => request('categorie')]) }}" 
                               class="btn {{ request('urgence') === 'general' ? 'btn-info' : 'btn-outline-info' }} btn-sm">
                                📌 Général
                            </a>
                            <a href="{{ route('etudiante.annonces', ['urgence' => 'urgent', 'categorie' => request('categorie')]) }}" 
                               class="btn {{ request('urgence') === 'urgent' ? 'btn-danger' : 'btn-outline-danger' }} btn-sm">
                                🔴 Urgent
                            </a>
                            <a href="{{ route('etudiante.annonces', ['urgence' => 'administration', 'categorie' => request('categorie')]) }}" 
                               class="btn {{ request('urgence') === 'administration' ? 'btn-dark' : 'btn-outline-dark' }} btn-sm">
                                👔 Administration
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== ANNONCES EN CARTES ===== -->
    @if($annonces->count() > 0)
        <div class="row g-4">
            @foreach($annonces as $annonce)
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 shadow-sm border-0 hover-shadow" style="transition: transform 0.2s, box-shadow 0.2s;">
                    
                    <!-- Badge Catégorie -->
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

                    <div class="card-header bg-white border-0 d-flex gap-2 flex-wrap">
                        <span class="badge bg-{{ $cat['couleur'] }}">{{ $cat['label'] }}</span>
                        <span class="badge bg-{{ $urg['couleur'] }}">{{ $urg['label'] }}</span>
                    </div>

                    <!-- Corps de la carte -->
                    <div class="card-body">
                        <h5 class="card-title">{{ $annonce->titre }}</h5>
                        <p class="card-text text-muted">
                            {{ Str::limit($annonce->contenu, 100, '...') }}
                        </p>
                    </div>

                    <!-- Footer -->
                    <div class="card-footer bg-light border-0">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="text-muted">
                                <i class="bi bi-calendar"></i> {{ $annonce->created_at->format('d/m/Y') }}
                            </small>
                            <small class="text-muted">
                                <i class="bi bi-person"></i> {{ $annonce->user->name ?? 'Admin' }}
                            </small>
                        </div>
                        <button class="btn btn-sm btn-primary w-100" 
                                data-bs-toggle="modal" 
                                data-bs-target="#annonce{{ $annonce->id }}">
                            Voir plus
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modal Détail -->
            <div class="modal fade" id="annonce{{ $annonce->id }}" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-light">
                            <h5 class="modal-title">{{ $annonce->titre }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <span class="badge bg-{{ $cat['couleur'] }}">{{ $cat['label'] }}</span>
                                <span class="badge bg-{{ $urg['couleur'] }}">{{ $urg['label'] }}</span>
                            </div>
                            <p>{{ $annonce->contenu }}</p>
                            <hr>
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted">
                                        <i class="bi bi-calendar"></i> 
                                        Publié le {{ $annonce->created_at->format('d/m/Y à H:i') }}
                                    </small>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">
                                        <i class="bi bi-person"></i> 
                                        Par {{ $annonce->user->name ?? 'Admin' }}
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

<style>
    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
</style>
@endsection