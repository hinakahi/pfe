@extends('layouts.app')

@section('title', 'Foyer - Promotions')
@section('page-title', 'Foyer')

@section('styles')
<style>
    .article-card {
        border-radius: 14px;
        overflow: hidden;
        transition: transform 0.2s, box-shadow 0.2s;
        height: 100%;
    }
    .article-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.13);
    }
    .article-img {
        width: 100%;
        height: 170px;
        object-fit: cover;
        background: #f0f4f8;
        position: relative;
    }
    .article-img-placeholder {
        width: 100%;
        height: 170px;
        background: linear-gradient(135deg, #e8f0fe, #d2e3fc);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        color: #4a90d9;
    }
    .promo-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        background: #dc3545;
        color: #fff;
        font-size: 0.72rem;
        font-weight: 700;
        padding: 5px 12px;
        border-radius: 20px;
        z-index: 10;
    }
    .stock-badge {
        font-size: 0.75rem;
        padding: 3px 10px;
        border-radius: 20px;
    }
    .prix-label {
        font-size: 1.15rem;
        font-weight: 700;
        color: #1a3c5e;
    }
    [data-theme="dark"] .prix-label { color: #7eb3e8; }
    [data-theme="dark"] .article-img-placeholder {
        background: linear-gradient(135deg, #1a2f4a, #1e3d5c);
        color: #7eb3e8;
    }
    .filter-bar {
        background: var(--bg-card);
        border-radius: 12px;
        padding: 14px 18px;
        margin-bottom: 20px;
        box-shadow: var(--shadow);
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        align-items: center;
    }
    .filter-bar input, .filter-bar select {
        border-radius: 8px;
        border: 1px solid #dee2e6;
        padding: 6px 12px;
        font-size: 0.9rem;
    }
    [data-theme="dark"] .filter-bar input,
    [data-theme="dark"] .filter-bar select {
        background: #2d3139;
        border-color: #444;
        color: var(--text-main);
    }
    .btn-reserver {
        background: linear-gradient(135deg, #d97706, #b45309);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 7px 16px;
        font-size: 0.85rem;
        font-weight: 600;
        width: 100%;
        transition: opacity 0.2s;
    }
    .btn-reserver:hover { opacity: 0.88; color: #fff; }
    .btn-reserver:disabled {
        background: #adb5bd;
        cursor: not-allowed;
    }
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: var(--text-muted);
    }
    .empty-state i { font-size: 3rem; margin-bottom: 12px; display: block; }
    .section-header {
        background: linear-gradient(135deg, #b45309, #d97706);
        color: #fff;
        padding: 20px;
        border-radius: 14px;
        margin-bottom: 24px;
    }
    .section-header h4 {
        margin: 0;
        font-weight: 700;
    }
    .section-header p {
        margin: 8px 0 0 0;
        opacity: 0.9;
        font-size: 0.95rem;
    }
</style>
@endsection

@section('content')

{{-- En-tête avec gradient --}}
{{-- Flèche de retour --}}
<a href="{{ route('etudiante.foyer.reservations') }}" class="btn btn-sm btn-outline-secondary mb-3">
    <i class="bi bi-arrow-left me-1"></i>Retour aux réservations
</a>
<div class="section-header">
    <h4>
        <i class="bi bi-tag-fill me-2"></i>Promotions en cours
    </h4>
   
</div>


{{-- Barre de filtre --}}
<div class="filter-bar">
    <i class="bi bi-search text-muted"></i>
    <input type="text" id="searchInput" placeholder="Rechercher une promotion..." style="min-width:200px; flex:1;">
    <select id="sortSelect" style="min-width:160px;">
        <option value="">Trier par...</option>
        <option value="prix-asc">Prix croissant</option>
        <option value="prix-desc">Prix décroissant</option>
        <option value="nom">Nom A→Z</option>
    </select>
    <span class="text-muted small ms-auto" id="countLabel">
        {{ $promotions->count() }} promotion(s)
    </span>
</div>

{{-- Grille des promotions --}}
@if($promotions->isEmpty())
    <div class="empty-state">
        <i class="bi bi-tag"></i>
        <h6>Aucune promotion disponible pour le moment</h6>
        <p class="small">Le responsable du foyer n'a pas encore ajouté de promotions. Revenez bientôt !</p>
        <a href="{{ route('etudiante.foyer.catalogue', 'tous') }}" class="btn btn-sm btn-primary mt-3">
            <i class="bi bi-shop me-1"></i>Voir tous les articles
        </a>
    </div>
@else
    <div class="row g-3" id="articlesGrid">
        @foreach($promotions as $article)
        <div class="col-6 col-md-4 col-lg-3 article-item"
             data-nom="{{ strtolower($article->nom_article) }}"
             data-prix="{{ $article->prix }}">
            <div class="card article-card h-100">

                {{-- Image avec badge PROMO --}}
                <div style="position: relative;">
                    @if($article->photo)
                        <img src="{{ asset('storage/' . $article->photo) }}"
                             alt="{{ $article->nom_article }}"
                             class="article-img">
                    @else
                        <div class="article-img-placeholder">
                            <i class="bi bi-box-seam"></i>
                        </div>
                    @endif
                    <span class="promo-badge">
                        <i class="bi bi-lightning-fill me-1"></i>PROMO
                    </span>
                </div>

                <div class="card-body d-flex flex-column gap-2 p-3">

                    {{-- Nom + stock --}}
                    <div class="d-flex justify-content-between align-items-start gap-1">
                        <h6 class="fw-semibold mb-0" style="font-size:.92rem; line-height:1.3;">
                            {{ $article->nom_article }}
                        </h6>
                        @if($article->stock > 5)
                            <span class="badge bg-success stock-badge">En stock</span>
                        @elseif($article->stock > 0)
                            <span class="badge bg-warning text-dark stock-badge">Stock faible</span>
                        @else
                            <span class="badge bg-danger stock-badge">Épuisé</span>
                        @endif
                    </div>

                    {{-- Description --}}
                    @if($article->description)
                    <p class="text-muted small mb-0" style="font-size:.8rem; line-height:1.4;">
                        {{ Str::limit($article->description, 60) }}
                    </p>
                    @endif

                    {{-- Prix + stock restant --}}
                    <div class="d-flex justify-content-between align-items-center mt-auto">
                        <span class="prix-label" style="color:#d97706;">{{ number_format($article->prix, 2) }} DA</span>
                        <span class="text-muted small">{{ $article->stock }} restant(s)</span>
                    </div>

                    {{-- Bouton réserver --}}
                    @if($article->stock > 0)
                    <button type="button"
                            class="btn-reserver"
                            data-bs-toggle="modal"
                            data-bs-target="#modalReserver"
                            data-id="{{ $article->id }}"
                            data-nom="{{ $article->nom_article }}"
                            data-prix="{{ $article->prix }}"
                            data-stock="{{ $article->stock }}">
                        <i class="bi bi-cart-plus me-1"></i>Réserver
                    </button>
                    @else
                    <button class="btn-reserver" disabled>
                        <i class="bi bi-x-circle me-1"></i>Indisponible
                    </button>
                    @endif

                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    @if($promotions->hasPages())
    <nav aria-label="Page navigation" class="mt-4">
        <ul class="pagination justify-content-center">
            {{-- Lien Précédent --}}
            @if($promotions->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link">← Précédent</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $promotions->previousPageUrl() }}">← Précédent</a>
                </li>
            @endif

            {{-- Liens des pages --}}
            @foreach($promotions->getUrlRange(1, $promotions->lastPage()) as $page => $url)
                @if($page == $promotions->currentPage())
                    <li class="page-item active">
                        <span class="page-link">{{ $page }}</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                    </li>
                @endif
            @endforeach

            {{-- Lien Suivant --}}
            @if($promotions->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $promotions->nextPageUrl() }}">Suivant →</a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link">Suivant →</span>
                </li>
            @endif
        </ul>
    </nav>
    @endif
@endif

{{-- ===== MODAL RÉSERVATION ===== --}}
<div class="modal fade" id="modalReserver" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:14px; border:none;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-cart-plus me-2" style="color:#d97706;"></i>
                    Réserver une promotion
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formReserver" method="POST" action="">
                @csrf
                <div class="modal-body pt-2">

                    <div class="p-3 rounded-3 mb-3"
                         style="background: linear-gradient(135deg,#fef3c7,#fde68a);">
                        <div class="fw-semibold" id="modalNomArticle" style="color:#b45309;"></div>
                        <div class="small text-muted mt-1">
                            Prix unitaire : <strong id="modalPrixArticle" style="color:#b45309;"></strong>
                        </div>
                    </div>

                    <label class="form-label fw-semibold small">Quantité souhaitée</label>
                    <div class="input-group">
                        <button type="button" class="btn btn-outline-secondary" id="btnMoins">
                            <i class="bi bi-dash"></i>
                        </button>
                        <input type="number" name="quantite" id="inputQuantite"
                               class="form-control text-center fw-bold"
                               value="1" min="1" max="1" readonly>
                        <button type="button" class="btn btn-outline-secondary" id="btnPlus">
                            <i class="bi bi-plus"></i>
                        </button>
                    </div>
                    <div class="small text-muted mt-1">
                        Stock disponible : <span id="modalStock" class="fw-semibold"></span>
                    </div>

                    <div class="mt-3 p-2 rounded-3 text-center"
                         style="background:var(--bg-body); border:1px dashed #d97706;">
                        <span class="small text-muted">Total estimé : </span>
                        <span class="fw-bold" id="totalEstime" style="color:#d97706; font-size:1.05rem;"></span>
                    </div>

                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn text-white fw-semibold"
                            style="background: linear-gradient(135deg,#d97706,#b45309); border:none; border-radius:8px;">
                        <i class="bi bi-check-circle me-1"></i>Confirmer la réservation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
// ── Remplir le modal ──
const modalReserver = document.getElementById('modalReserver');
modalReserver.addEventListener('show.bs.modal', e => {
    const btn = e.relatedTarget;
    const id    = btn.dataset.id;
    const nom   = btn.dataset.nom;
    const prix  = parseFloat(btn.dataset.prix);
    const stock = parseInt(btn.dataset.stock);

    document.getElementById('modalNomArticle').textContent = nom;
    document.getElementById('modalPrixArticle').textContent = prix.toFixed(2) + ' DA';
    document.getElementById('modalStock').textContent = stock;
    document.getElementById('inputQuantite').max = stock;
    document.getElementById('inputQuantite').value = 1;
    document.getElementById('totalEstime').textContent = prix.toFixed(2) + ' DA';
    document.getElementById('formReserver').action = `/etudiante/foyer/reserver/${id}`;
});

// ── +/- quantité ──
const inputQte = document.getElementById('inputQuantite');
function updateTotal() {
    const prix = parseFloat(document.getElementById('modalPrixArticle').textContent);
    const qte  = parseInt(inputQte.value);
    document.getElementById('totalEstime').textContent = (prix * qte).toFixed(2) + ' DA';
}
document.getElementById('btnMoins').addEventListener('click', () => {
    if (inputQte.value > 1) { inputQte.value--; updateTotal(); }
});
document.getElementById('btnPlus').addEventListener('click', () => {
    if (inputQte.value < inputQte.max) { inputQte.value++; updateTotal(); }
});

// ── Recherche ──
document.getElementById('searchInput').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.article-item').forEach(el => {
        el.style.display = el.dataset.nom.includes(q) ? '' : 'none';
    });
    updateCount();
});

// ── Tri ──
document.getElementById('sortSelect').addEventListener('change', function() {
    const grid = document.getElementById('articlesGrid');
    const items = [...grid.querySelectorAll('.article-item')];
    items.sort((a, b) => {
        if (this.value === 'prix-asc')  return a.dataset.prix - b.dataset.prix;
        if (this.value === 'prix-desc') return b.dataset.prix - a.dataset.prix;
        if (this.value === 'nom')       return a.dataset.nom.localeCompare(b.dataset.nom);
        return 0;
    });
    items.forEach(el => grid.appendChild(el));
});

function updateCount() {
    const visible = document.querySelectorAll('.article-item:not([style*="none"])').length;
    document.getElementById('countLabel').textContent = visible + ' promotion(s)';
}
</script>
@endsection