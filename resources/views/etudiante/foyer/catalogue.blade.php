@extends('layouts.app')

@section('title', 'Catalogue - Foyer')
@section('page-title', 'Foyer')

@section('styles')
<style>
    .catalogue-header {
        background: linear-gradient(135deg, #1a3c5e, #2d6a9f);
        border-radius: 16px;
        padding: 24px 28px;
        color: #fff;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }
    .catalogue-header::after {
        content: '\F3C2';
        font-family: 'bootstrap-icons';
        position: absolute;
        right: 24px; top: 50%;
        transform: translateY(-50%);
        font-size: 5rem;
        opacity: 0.1;
    }
    .catalogue-header h4 { font-weight: 800; margin-bottom: 4px; }
    .catalogue-header p  { opacity: .8; font-size: .88rem; margin: 0; }

    /* Catégories */
    .cat-tabs {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }
    .cat-tab {
        border: none;
        border-radius: 20px;
        padding: 6px 16px;
        font-size: 0.83rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        background: var(--bg-card);
        color: var(--text-muted);
        box-shadow: var(--shadow);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .cat-tab:hover, .cat-tab.active {
        background: linear-gradient(135deg, #1a3c5e, #2d6a9f);
        color: #fff;
        box-shadow: 0 4px 12px rgba(45,106,159,0.35);
    }

    /* Barre recherche */
    .search-bar {
        background: var(--bg-card);
        border-radius: 12px;
        padding: 12px 16px;
        margin-bottom: 20px;
        box-shadow: var(--shadow);
        display: flex;
        gap: 10px;
        align-items: center;
        flex-wrap: wrap;
    }
    .search-bar input, .search-bar select {
        border-radius: 8px;
        border: 1px solid #dee2e6;
        padding: 7px 12px;
        font-size: .875rem;
        background: var(--bg-body);
        color: var(--text-main);
    }
    [data-theme="dark"] .search-bar input,
    [data-theme="dark"] .search-bar select {
        background: #2d3139;
        border-color: #444;
        color: var(--text-main);
    }

    /* Carte article */
    .art-card {
        border-radius: 14px;
        overflow: hidden;
        border: none;
        height: 100%;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .art-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 28px rgba(0,0,0,0.13);
    }
    .art-img {
        width: 100%; height: 160px;
        object-fit: cover;
    }
    .art-img-ph {
        width: 100%; height: 160px;
        background: linear-gradient(135deg, #e8f0fe, #d2e3fc);
        display: flex; align-items: center; justify-content: center;
        font-size: 2.5rem; color: #4a90d9;
    }
    [data-theme="dark"] .art-img-ph {
        background: linear-gradient(135deg, #1a2f4a, #1e3d5c);
        color: #7eb3e8;
    }
    .art-img-ph.alimentaire { background: linear-gradient(135deg,#fef3c7,#fde68a); color:#b45309; }
    .art-img-ph.hygiene     { background: linear-gradient(135deg,#dbeafe,#bfdbfe); color:#1d4ed8; }
    .art-img-ph.entretien   { background: linear-gradient(135deg,#d1fae5,#a7f3d0); color:#065f46; }
    .art-img-ph.autre       { background: linear-gradient(135deg,#f3e8ff,#e9d5ff); color:#7c3aed; }

    .stock-dot {
        width: 8px; height: 8px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 4px;
    }
    .prix-art {
        font-size: 1.1rem;
        font-weight: 800;
        color: #1a3c5e;
    }
    [data-theme="dark"] .prix-art { color: #7eb3e8; }

    .promo-ribbon {
        position: absolute;
        top: 10px; right: 10px;
        background: #dc3545;
        color: #fff;
        font-size: .7rem;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 20px;
    }
    .btn-add-cart {
        width: 100%;
        background: linear-gradient(135deg, #1a3c5e, #2d6a9f);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 8px;
        font-size: .85rem;
        font-weight: 600;
        transition: opacity .2s;
    }
    .btn-add-cart:hover { opacity: .88; color: #fff; }
    .btn-add-cart:disabled { background: #adb5bd; cursor: not-allowed; }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: var(--text-muted);
    }
    .empty-state i { font-size: 3rem; display: block; margin-bottom: 10px; }

    /* Vue liste */
    .list-art-row {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 12px;
        border-radius: 12px;
        transition: background .15s;
    }
    .list-art-row:hover { background: var(--bg-body); }
    .list-thumb {
        width: 60px; height: 60px;
        border-radius: 10px; object-fit: cover; flex-shrink: 0;
    }
    .list-thumb-ph {
        width: 60px; height: 60px;
        border-radius: 10px; flex-shrink: 0;
        background: linear-gradient(135deg,#e8f0fe,#d2e3fc);
        display: flex; align-items: center; justify-content: center;
        color: #4a90d9; font-size: 1.4rem;
    }
    .vue-toggle button {
        border: none;
        background: var(--bg-card);
        border-radius: 8px;
        padding: 5px 10px;
        color: var(--text-muted);
        box-shadow: var(--shadow);
        cursor: pointer;
        transition: all .2s;
    }
    .vue-toggle button.active {
        background: #1a3c5e;
        color: #fff;
    }
</style>
@endsection

@section('content')

{{-- En-tête --}}
{{-- Flèche de retour --}}
<a href="{{ route('etudiante.foyer.reservations') }}" class="btn btn-sm btn-outline-secondary mb-3">
    <i class="bi bi-arrow-left me-1"></i>Retour aux réservations
</a>
<div class="catalogue-header">
    <h4><i class="bi bi-shop me-2"></i>Catalogue du Foyer</h4>
    <p>Parcourez les articles disponibles et ajoutez-les à votre panier</p>
</div>

{{-- Catégories --}}
@php
    $categories = [
        'tous'       => ['label' => 'Tous',       'icon' => 'bi-grid'],
        'cafeteria'  => ['label' => 'Cafétéria',  'icon' => 'bi-cup-hot'],
        'fastfood'   => ['label' => 'Fastfood',   'icon' => 'bi-basket'],
        'magasin'    => ['label' => 'Magasin',    'icon' => 'bi-shop'],
    ];
@endphp
<div class="cat-tabs">
    @foreach($categories as $key => $cat)
    <a href="{{ route('etudiante.foyer.catalogue', $key) }}"
       class="cat-tab {{ $categorie === $key ? 'active' : '' }}">
        <i class="bi {{ $cat['icon'] }}"></i>
        {{ $cat['label'] }}
    </a>
    @endforeach
</div>

{{-- Barre recherche + tri + vue --}}
<div class="search-bar">
    <i class="bi bi-search text-muted"></i>
    <input type="text" id="searchInput" placeholder="Rechercher un article..."
           style="flex:1; min-width:160px;">
    <select id="sortSelect" style="min-width:150px;">
        <option value="">Trier par...</option>
        <option value="prix-asc">Prix croissant</option>
        <option value="prix-desc">Prix décroissant</option>
        <option value="nom">Nom A→Z</option>
        <option value="stock">Stock disponible</option>
    </select>
    <div class="vue-toggle d-flex gap-1 ms-auto">
        <button id="btnGrid" class="active" title="Vue grille">
            <i class="bi bi-grid-3x3-gap"></i>
        </button>
        <button id="btnList" title="Vue liste">
            <i class="bi bi-list-ul"></i>
        </button>
    </div>
    <span class="text-muted small" id="countLabel" style="white-space:nowrap;">
        {{ $articles->count() }} article(s)
    </span>
</div>

{{-- ===== VUE GRILLE ===== --}}
@if($articles->isEmpty())
<div class="empty-state">
    <i class="bi bi-box-seam"></i>
    <h6>Aucun article disponible</h6>
    <p class="small">Le responsable du foyer n'a pas encore ajouté d'articles.</p>
</div>
@else

<div id="viewGrid">
    <div class="row g-3" id="articlesGrid">
        @foreach($articles as $article)
        <div class="col-6 col-md-4 col-xl-3 art-item"
             data-nom="{{ strtolower($article->nom_article) }}"
             data-prix="{{ $article->prix_actuel }}"
             data-stock="{{ $article->stock }}">
            <div class="card art-card">

                {{-- Image --}}
                <div style="position:relative;">
                    @if($article->photo)
                        <img src="{{ asset('storage/'.$article->photo) }}"
                             alt="{{ $article->nom_article }}"
                             class="art-img">
                    @else
                        <div class="art-img-ph {{ $categorie !== 'tous' ? $categorie : 'autre' }}">
                            @switch($categorie)
                                @case('alimentaire') <i class="bi bi-basket"></i> @break
                                @case('hygiene')     <i class="bi bi-droplet"></i> @break
                                @case('entretien')   <i class="bi bi-brush"></i> @break
                                @default             <i class="bi bi-box-seam"></i>
                            @endswitch
                        </div>
                    @endif
                    @if($article->promo_active ?? false)
                        <span class="promo-ribbon">PROMO</span>
                    @endif
                </div>

                <div class="card-body p-3 d-flex flex-column gap-2">

                    {{-- Nom + stock --}}
                    <div class="d-flex justify-content-between align-items-start gap-1">
                        <h6 class="fw-semibold mb-0" style="font-size:.88rem; line-height:1.3;">
                            {{ $article->nom_article }}
                        </h6>
                        <span class="small" style="white-space:nowrap;">
                            @if($article->stock > 5)
                                <span class="stock-dot" style="background:#198754;"></span>
                                <span class="text-success" style="font-size:.75rem;">Dispo</span>
                            @elseif($article->stock > 0)
                                <span class="stock-dot" style="background:#ffc107;"></span>
                                <span class="text-warning" style="font-size:.75rem;">Faible</span>
                            @else
                                <span class="stock-dot" style="background:#dc3545;"></span>
                                <span class="text-danger" style="font-size:.75rem;">Épuisé</span>
                            @endif
                        </span>
                    </div>

                    {{-- Description --}}
                    @if($article->description)
                    <p class="text-muted mb-0" style="font-size:.78rem; line-height:1.4;">
                        {{ Str::limit($article->description, 55) }}
                    </p>
                    @endif
                    {{-- Date de péremption --}}
@if($article->date_peremption)
    <div class="text-muted" style="font-size:.75rem;">
        <i class="bi bi-calendar-event me-1"></i>
        Expire le {{ $article->date_peremption->format('d/m/Y') }}
    </div>
@endif

                    {{-- Prix --}}
                    <div class="d-flex justify-content-between align-items-center mt-auto">
                        <span class="prix-art">{{ number_format($article->prix_actuel, 2) }} DA</span>
                        <span class="text-muted" style="font-size:.78rem;">
                            {{ $article->stock }} restant(s)
                        </span>
                    </div>

                    {{-- Bouton --}}
                    @if($article->stock > 0)
                    <button type="button"
                            class="btn-add-cart"
                            data-bs-toggle="modal"
                            data-bs-target="#modalPanier"
                            data-id="{{ $article->id }}"
                            data-nom="{{ $article->nom_article }}"
                            data-prix="{{ $article->prix_actuel }}"
                            data-stock="{{ $article->stock }}">
                        <i class="bi bi-cart-plus me-1"></i>Ajouter au panier
                    </button>
                    @else
                    <button class="btn-add-cart" disabled>
                        <i class="bi bi-x-circle me-1"></i>Indisponible
                    </button>
                    @endif

                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- ===== VUE LISTE ===== --}}
<div id="viewList" style="display:none;">
    <div class="card p-2" id="articlesList">
        @foreach($articles as $article)
        <div class="list-art-row art-item"
             data-nom="{{ strtolower($article->nom_article) }}"
             data-prix="{{ $article->prix_actuel }}"
             data-stock="{{ $article->stock }}">

            @if($article->photo)
                <img src="{{ asset('storage/'.$article->photo) }}"
                     alt="{{ $article->nom_article }}"
                     class="list-thumb">
            @else
                <div class="list-thumb-ph"><i class="bi bi-box-seam"></i></div>
            @endif

            <div class="flex-grow-1">
                <div class="fw-semibold" style="font-size:.9rem;">
                    {{ $article->nom_article }}
                    @if($article->promo_active ?? false)
                        <span class="badge bg-danger ms-1" style="font-size:.65rem;">PROMO</span>
                    @endif
                </div>
                @if($article->description)
                <div class="text-muted" style="font-size:.78rem;">
                    {{ Str::limit($article->description, 70) }}
                </div>
                @endif
            </div>

            <div class="text-end" style="min-width:90px;">
                <div class="prix-art">{{ number_format($article->prix_actuel, 2) }} DA</div>
                <div class="text-muted" style="font-size:.75rem;">
                    Stock : {{ $article->stock }}
                </div>
            </div>

            @if($article->stock > 0)
            <button type="button"
                    class="btn btn-primary btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#modalPanier"
                    data-id="{{ $article->id }}"
                    data-nom="{{ $article->nom_article }}"
                    data-prix="{{ $article->prix_actuel }}"
                    data-stock="{{ $article->stock }}"
                    style="white-space:nowrap;">
                <i class="bi bi-cart-plus me-1"></i>Ajouter
            </button>
            @else
            <button class="btn btn-secondary btn-sm" disabled>Épuisé</button>
            @endif

        </div>
        @endforeach
    </div>
</div>

{{-- Pagination --}}
<div class="d-flex justify-content-center mt-4">
    {{ $articles->links() }}
</div>

@endif

{{-- ===== MODAL PANIER ===== --}}
<div class="modal fade" id="modalPanier" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px; border:none;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-cart-plus me-2" style="color:#2d6a9f;"></i>
                    Ajouter au panier
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="formPanier" action="">
                @csrf
                <div class="modal-body pt-2">

                    {{-- Récap article --}}
                    <div class="p-3 rounded-3 mb-4"
                         style="background:linear-gradient(135deg,#e8f0fe,#d2e3fc);">
                        <div class="fw-bold" id="mNom" style="color:#1a3c5e; font-size:1rem;"></div>
                        <div class="d-flex justify-content-between mt-1">
                            <span class="small text-muted">Prix unitaire</span>
                            <span class="fw-bold" id="mPrix" style="color:#1a3c5e;"></span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="small text-muted">Stock disponible</span>
                            <span class="fw-semibold" id="mStock"></span>
                        </div>
                    </div>

                    {{-- Quantité --}}
                    <label class="form-label fw-semibold small mb-2">Quantité</label>
                    <div class="input-group" style="max-width:180px; margin: 0 auto;">
                        <button type="button" class="btn btn-outline-secondary" id="btnM">
                            <i class="bi bi-dash"></i>
                        </button>
                        <input type="number" name="quantite" id="mQte"
                               class="form-control text-center fw-bold fs-5"
                               value="1" min="1" max="1" readonly>
                        <button type="button" class="btn btn-outline-secondary" id="btnP">
                            <i class="bi bi-plus"></i>
                        </button>
                    </div>

                    {{-- Total --}}
                    <div class="text-center mt-4 p-3 rounded-3"
                         style="background:var(--bg-body); border:2px dashed #2d6a9f;">
                        <div class="small text-muted mb-1">Total estimé</div>
                        <div id="mTotal"
                             style="font-size:1.4rem; font-weight:800; color:#1a3c5e;"></div>
                    </div>

                </div>
                <div class="modal-footer border-0 pt-0 gap-2">
                    <button type="button" class="btn btn-light flex-fill"
                            data-bs-dismiss="modal">Annuler</button>
                    <button type="submit"
                            class="btn flex-fill text-white fw-bold"
                            style="background:linear-gradient(135deg,#1a3c5e,#2d6a9f);
                                   border:none; border-radius:8px;">
                        <i class="bi bi-cart-check me-1"></i>Ajouter au panier
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ===== PANIER STICKY ===== --}}
@if(auth()->check())
@php
    $panierCount    = \App\Models\Reservation::where('etudiante_id', auth()->id())->where('statut','panier')->count();
    $panierTotal    = \App\Models\Reservation::where('etudiante_id', auth()->id())->where('statut','panier')->get()->sum(fn($r) => $r->article->prix * $r->quantite);
    $panierArticles = \App\Models\Reservation::where('etudiante_id', auth()->id())->where('statut','panier')->get();
@endphp

{{-- Bouton sticky --}}
<button type="button" id="panierBtn"
        style="position:fixed; bottom:30px; right:30px; width:60px; height:60px; border-radius:50%; background:linear-gradient(135deg,#1a3c5e,#2d6a9f); color:#fff; border:none; cursor:pointer; box-shadow:0 4px 12px rgba(0,0,0,0.3); z-index:999; display:flex; align-items:center; justify-content:center; font-size:1.8rem;"
        data-bs-toggle="modal" data-bs-target="#modalPanierSticky">
    <i class="bi bi-cart3"></i>
    <span class="badge-count" style="position:absolute; top:-8px; right:-8px; background:#dc3545; color:#fff; border-radius:50%; width:28px; height:28px; display:flex; align-items:center; justify-content:center; font-size:0.8rem; font-weight:bold;">{{ $panierCount }}</span>
</button>

{{-- Modal panier sticky --}}
<div class="modal fade" id="modalPanierSticky" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-cart3 me-2"></i>Mon Panier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="panierModalBody">
                @forelse($panierArticles as $item)
                <div id="panier-item-{{ $item->id }}"
                     data-prix="{{ $item->article->prix }}"
                     data-qte="{{ $item->quantite }}"
                     style="display:flex; align-items:center; gap:12px; padding:12px 0; border-bottom:1px solid #dee2e6; transition:opacity 0.3s;">
                    @if($item->article->photo)
                        <img src="{{ asset('storage/'.$item->article->photo) }}"
                             style="width:60px; height:60px; object-fit:cover; border-radius:8px;">
                    @else
                        <div style="width:60px; height:60px; background:linear-gradient(135deg,#e8f0fe,#d2e3fc); border-radius:8px; display:flex; align-items:center; justify-content:center;">
                            <i class="bi bi-box-seam" style="color:#4a90d9;"></i>
                        </div>
                    @endif
                    <div style="flex:1;">
                        <strong>{{ $item->article->nom_article }}</strong><br>
                        <span style="color:#666; font-size:.9rem;">Qté: {{ $item->quantite }} × {{ number_format($item->article->prix,2) }} DA</span>
                    </div>
                    <strong style="color:#1a3c5e;">{{ number_format($item->article->prix * $item->quantite,2) }} DA</strong>
                    <button type="button" class="btn btn-outline-danger btn-sm"
                            onclick="supprimerArticlePanier({{ $item->id }})">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                @empty
                <p class="text-center text-muted">Panier vide</p>
                @endforelse
            </div>
            <div class="modal-footer" style="border-top:2px solid #dee2e6;">
                <div style="flex:1;">
                    <strong class="total-label"
                            data-total="{{ $panierTotal }}"
                            style="font-size:1.2rem; color:#1a3c5e;">
                        Total : {{ number_format($panierTotal,2) }} DA
                    </strong>
                </div>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <form method="POST" action="{{ route('etudiante.foyer.confirmer') }}" style="display:inline;">
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

@endsection


@section('scripts')
<script>
// ── Modal panier ──
const modalEl = document.getElementById('modalPanier');
modalEl.addEventListener('show.bs.modal', e => {
    const b = e.relatedTarget;
    const prix  = parseFloat(b.dataset.prix);
    const stock = parseInt(b.dataset.stock);

    document.getElementById('mNom').textContent   = b.dataset.nom;
    document.getElementById('mPrix').textContent  = prix.toFixed(2) + ' DA';
    document.getElementById('mStock').textContent = stock;
    document.getElementById('mQte').max   = stock;
    document.getElementById('mQte').value = 1;
    document.getElementById('mTotal').textContent = prix.toFixed(2) + ' DA';
    document.getElementById('formPanier').action =
        `/etudiante/foyer/reserver/${b.dataset.id}`;
});

const qte = document.getElementById('mQte');
function calcTotal() {
    const prix = parseFloat(document.getElementById('mPrix').textContent);
    document.getElementById('mTotal').textContent =
        (prix * parseInt(qte.value)).toFixed(2) + ' DA';
}
document.getElementById('btnM').addEventListener('click', () => {
    if (qte.value > 1) { qte.value--; calcTotal(); }
});
document.getElementById('btnP').addEventListener('click', () => {
    if (parseInt(qte.value) < parseInt(qte.max)) { qte.value++; calcTotal(); }
});

// ── Recherche ──
document.getElementById('searchInput').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.art-item').forEach(el => {
        el.style.display = el.dataset.nom.includes(q) ? '' : 'none';
    });
    updateCount();
});

// ── Tri ──
document.getElementById('sortSelect').addEventListener('change', function() {
    ['articlesGrid','articlesList'].forEach(id => {
        const container = document.getElementById(id);
        if (!container) return;
        const selector = id === 'articlesGrid' ? '.art-item' : '.list-art-row';
        const items = [...container.querySelectorAll(selector)];
        items.sort((a, b) => {
            if (this.value === 'prix-asc')  return a.dataset.prix - b.dataset.prix;
            if (this.value === 'prix-desc') return b.dataset.prix - a.dataset.prix;
            if (this.value === 'nom')       return a.dataset.nom.localeCompare(b.dataset.nom);
            if (this.value === 'stock')     return b.dataset.stock - a.dataset.stock;
            return 0;
        });
        items.forEach(el => container.appendChild(el));
    });
});

// ── Vue grille / liste ──
document.getElementById('btnGrid').addEventListener('click', function() {
    document.getElementById('viewGrid').style.display = '';
    document.getElementById('viewList').style.display = 'none';
    document.getElementById('btnGrid').classList.add('active');
    document.getElementById('btnList').classList.remove('active');
});
document.getElementById('btnList').addEventListener('click', function() {
    document.getElementById('viewList').style.display = '';
    document.getElementById('viewGrid').style.display = 'none';
    document.getElementById('btnList').classList.add('active');
    document.getElementById('btnGrid').classList.remove('active');
});

function updateCount() {
    const n = document.querySelectorAll('.art-item:not([style*="none"])').length;
    document.getElementById('countLabel').textContent = n + ' article(s)';
}
// ── Badge panier ──
function updateBadge(delta) {
    const badge = document.querySelector('#panierBtn .badge-count');
    if (!badge) return;
    let count = parseInt(badge.textContent) + delta;
    if (count < 0) count = 0;
    badge.textContent = count;
}

// ── Supprimer du panier ──
function supprimerArticlePanier(id) {
    if (!confirm('Supprimer cet article du panier ?')) return;

    const element = document.getElementById(`panier-item-${id}`);
    if (!element) return;

    const soustrait = parseFloat(element.dataset.prix) * parseFloat(element.dataset.qte);
    element.style.opacity = '0';
    setTimeout(() => element.remove(), 300);

    const totalEl = document.querySelector('.total-label');
    if (totalEl) {
        const nouveau = Math.max(0, parseFloat(totalEl.dataset.total) - soustrait);
        totalEl.dataset.total = nouveau;
        totalEl.textContent = `Total : ${nouveau.toFixed(2)} DA`;
    }

    updateBadge(-1);

    fetch(`/etudiante/foyer/annuler/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(() => {
        const restants = document.querySelectorAll('[id^="panier-item-"]').length;
        if (restants === 0) {
            document.getElementById('panierModalBody').innerHTML =
                '<p class="text-center text-muted">Panier vide</p>';
            if (totalEl) { totalEl.textContent = 'Total : 0.00 DA'; totalEl.dataset.total = '0'; }
        }
    })
    .catch(err => console.error(err));
}
</script>
@endsection