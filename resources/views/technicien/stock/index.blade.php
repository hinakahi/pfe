@extends('layouts.app')

@section('page-title', 'Gestion du stock')

@section('content')
<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="mb-0">Matériels en stock</h5>
            <small class="text-muted" id="compteur">{{ $stocks->count() }} matériel(s) enregistré(s)</small>
        </div>
        <a href="{{ route('technicien.stock.create') }}" class="btn btn-primary rounded-pill">
            <i class="bi bi-plus-lg me-1"></i>Ajouter un matériel
        </a>
    </div>

    {{-- Alertes stock faible --}}
    @if($stocks->filter(fn($s) => $s->est_faible || $s->est_epuise)->count())
    <div class="alert alert-warning mb-4">
        <i class="bi bi-exclamation-triangle me-2"></i>
        <strong>Attention !</strong>
        {{ $stocks->filter(fn($s) => $s->est_faible || $s->est_epuise)->count() }}
        matériel(s) sous le seuil minimum ou épuisé(s).
    </div>
    @endif

    {{-- ══════════════════════════════════════════
         BARRE DE RECHERCHE + FILTRES
    ══════════════════════════════════════════ --}}
    <div class="card mb-4">
        <div class="card-body py-3">
            <div class="row g-2 align-items-center">

                {{-- Recherche par nom --}}
                <div class="col-12 col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" id="searchInput"
                               class="form-control border-start-0 ps-0"
                               placeholder="Rechercher un matériel…"
                               oninput="filtrerStock()">
                    </div>
                </div>

                {{-- Filtre Catégorie --}}
                <div class="col-6 col-md-3">
                    <select id="filtreCategorie" class="form-select" onchange="filtrerStock()">
                        <option value="">Toutes les catégories</option>
                        <option value="electricite">Électricité</option>
                        <option value="plomberie">Plomberie</option>
                        <option value="menuiserie">Menuiserie</option>
                        <option value="autre">Autre</option>
                    </select>
                </div>

                {{-- Filtre Statut --}}
                <div class="col-6 col-md-3">
                    <select id="filtreStatut" class="form-select" onchange="filtrerStock()">
                        <option value="">Tous les statuts</option>
                        <option value="disponible">Disponible</option>
                        <option value="faible">Stock faible</option>
                        <option value="epuise">Épuisé</option>
                    </select>
                </div>

                {{-- Bouton Reset --}}
                <div class="col-12 col-md-1 text-end">
                    <button class="btn btn-outline-secondary w-100" onclick="resetFiltres()" title="Réinitialiser">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

            </div>
        </div>
    </div>

    {{-- Message aucun résultat --}}
    <div id="aucunResultat" class="d-none">
        <div class="card">
            <div class="card-body text-center text-muted py-5">
                <i class="bi bi-search fs-1 opacity-25"></i>
                <p class="mt-2">Aucun matériel ne correspond à votre recherche.</p>
                <button class="btn btn-outline-secondary rounded-pill" onclick="resetFiltres()">
                    <i class="bi bi-x-lg me-1"></i>Réinitialiser les filtres
                </button>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════
         LISTE DES MATÉRIELS
    ══════════════════════════════════════════ --}}
    <div class="row g-3" id="stockGrid">
    @forelse($stocks as $stock)
        <div class="col-md-6 col-xl-4 stock-item"
             data-nom="{{ strtolower($stock->designation) }}"
             data-categorie="{{ $stock->categorie }}"
             data-statut="{{ $stock->est_epuise ? 'epuise' : ($stock->est_faible ? 'faible' : 'disponible') }}">
            <div class="card h-100 {{ $stock->est_epuise ? 'border-danger' : ($stock->est_faible ? 'border-warning' : '') }}">
            @if($stock->photo)
    <div class="card-img-top stock-photo-wrap">
        <img src="{{ Storage::url($stock->photo) }}"
             alt="{{ $stock->designation }}"
             class="stock-photo-img"
             loading="lazy">
    </div>
@else
    <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
         style="height:160px;">
        <i class="bi bi-image text-muted" style="font-size:2.5rem; opacity:.3;"></i>
    </div>
@endif
            <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h6 class="fw-bold mb-0">{{ $stock->designation }}</h6>
                            <small class="text-muted">{{ ucfirst($stock->categorie) }}</small>
                        </div>
                        @if($stock->est_epuise)
                            <span class="badge bg-danger">Épuisé</span>
                        @elseif($stock->est_faible)
                            <span class="badge bg-warning text-dark">Stock faible</span>
                        @else
                            <span class="badge bg-success">Disponible</span>
                        @endif
                    </div>

                    <div class="d-flex align-items-end gap-1 my-3">
                        <span style="font-size:2rem;font-weight:800;line-height:1;
                            color:{{ $stock->est_epuise ? '#dc2626' : ($stock->est_faible ? '#d97706' : '#16a34a') }}">
                            {{ $stock->quantite }}
                        </span>
                        <span class="text-muted mb-1">{{ $stock->unite }}</span>
                    </div>

                    <div class="text-muted mb-3" style="font-size:.8rem;">
                        <i class="bi bi-arrow-down-circle me-1"></i>Seuil minimum : {{ $stock->seuil_minimum }} {{ $stock->unite }}
                    </div>

                    @if($stock->description)
                    <p class="text-muted" style="font-size:.8rem;">{{ $stock->description }}</p>
                    @endif

                    <div class="d-flex gap-2 mt-auto">
                        <a href="{{ route('technicien.stock.edit', $stock->id) }}"
                           class="btn btn-sm btn-outline-primary w-100">
                            <i class="bi bi-pencil me-1"></i>Modifier
                        </a>
                        <form method="POST" action="{{ route('technicien.stock.destroy', $stock->id) }}"
                              onsubmit="return confirm('Supprimer ce matériel ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center text-muted py-5">
                    <i class="bi bi-box-seam fs-1 opacity-25"></i>
                    <p class="mt-2">Aucun matériel en stock.</p>
                    <a href="{{ route('technicien.stock.create') }}" class="btn btn-primary rounded-pill">
                        <i class="bi bi-plus-lg me-1"></i>Ajouter un matériel
                    </a>
                </div>
            </div>
        </div>
    @endforelse
    </div>

</div>
<style>
.stock-photo-wrap {
    height: 140px;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 14px;
    overflow: hidden;
}
.stock-photo-img {
    max-height: 100%;
    max-width: 100%;
    object-fit: contain;
}
</style>

{{-- ══════════════════════════════════════════
     SCRIPT DE FILTRAGE (côté client)
══════════════════════════════════════════ --}}
<script>
function filtrerStock() {
    const recherche   = document.getElementById('searchInput').value.toLowerCase().trim();
    const categorie   = document.getElementById('filtreCategorie').value;
    const statut      = document.getElementById('filtreStatut').value;

    const items       = document.querySelectorAll('.stock-item');
    let   nbVisible   = 0;

    items.forEach(item => {
        const nom      = item.dataset.nom;
        const cat      = item.dataset.categorie;
        const stat     = item.dataset.statut;

        const matchNom      = nom.includes(recherche);
        const matchCat      = !categorie || cat === categorie;
        const matchStatut   = !statut   || stat === statut;

        if (matchNom && matchCat && matchStatut) {
            item.classList.remove('d-none');
            nbVisible++;
        } else {
            item.classList.add('d-none');
        }
    });

    // Mise à jour compteur
    document.getElementById('compteur').textContent =
        nbVisible + ' matériel(s) affiché(s)';

    // Message aucun résultat
    document.getElementById('aucunResultat').classList.toggle('d-none', nbVisible > 0);
    document.getElementById('stockGrid').classList.toggle('d-none', nbVisible === 0);
}

function resetFiltres() {
    document.getElementById('searchInput').value     = '';
    document.getElementById('filtreCategorie').value = '';
    document.getElementById('filtreStatut').value    = '';
    filtrerStock();
}
</script>

@endsection