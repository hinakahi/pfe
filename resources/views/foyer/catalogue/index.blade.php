@extends('layouts.app')

@section('title', 'Catalogue des Articles')

@section('sidebar')
    @include('foyer.partials._sidebar')
@endsection

@section('page-title', 'Catalogue des Articles')

@section('content')

<style>
    .stat-card {
        border-radius: 16px;
        padding: 1.5rem;
        color: #fff;
        position: relative;
        overflow: hidden;
        border: none;
        min-height: 110px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .stat-card .stat-label {
        font-size: 0.85rem;
        font-weight: 500;
        opacity: 0.88;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .stat-card .stat-value {
        font-size: 2.4rem;
        font-weight: 700;
        line-height: 1;
        margin-top: 0.5rem;
    }
    .stat-card .stat-icon {
        position: absolute;
        right: 1.2rem;
        top: 50%;
        transform: translateY(-50%);
        font-size: 3.5rem;
        opacity: 0.15;
    }
    .stat-blue   { background: linear-gradient(135deg, #1a4fa0 0%, #2979d8 100%); }
    .stat-green  { background: linear-gradient(135deg, #0d7a4e 0%, #1aad72 100%); }
    .stat-orange { background: linear-gradient(135deg, #b85c00 0%, #f5820d 100%); }
    .stat-red    { background: linear-gradient(135deg, #9b1c1c 0%, #e53e3e 100%); }

    .catalogue-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.07);
        border: none;
        padding: 1.75rem;
    }

    .search-bar {
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        padding: 0.55rem 1rem;
        font-size: 0.95rem;
        transition: border-color 0.2s;
    }
    .search-bar:focus {
        border-color: #2979d8;
        box-shadow: 0 0 0 3px rgba(41,121,216,0.10);
        outline: none;
    }

    .filter-select {
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        padding: 0.55rem 1rem;
        font-size: 0.95rem;
        background: #fff;
        transition: border-color 0.2s;
    }
    .filter-select:focus {
        border-color: #2979d8;
        box-shadow: 0 0 0 3px rgba(41,121,216,0.10);
        outline: none;
    }

    .btn-add-article {
        background: linear-gradient(135deg, #1a4fa0, #2979d8);
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 0.55rem 1.3rem;
        font-size: 0.95rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        transition: opacity 0.2s, transform 0.15s;
        text-decoration: none;
    }
    .btn-add-article:hover {
        opacity: 0.92;
        color: #fff;
        transform: translateY(-1px);
    }

    .catalogue-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    .catalogue-table thead th {
        font-size: 0.78rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        color: #64748b;
        padding: 0.65rem 1rem;
        background: #f8fafc;
        border-bottom: 1.5px solid #e2e8f0;
    }
    .catalogue-table thead th:first-child { border-radius: 10px 0 0 10px; }
    .catalogue-table thead th:last-child  { border-radius: 0 10px 10px 0; }

    .catalogue-table tbody tr {
        transition: background 0.15s;
    }
    .catalogue-table tbody tr:hover {
        background: #f0f6ff;
    }
    .catalogue-table tbody td {
        padding: 0.85rem 1rem;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        font-size: 0.93rem;
        color: #1e293b;
    }
    .catalogue-table tbody tr:last-child td {
        border-bottom: none;
    }

    .article-photo {
        width: 52px;
        height: 52px;
        object-fit: cover;
        border-radius: 10px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.10);
    }
    .article-photo-placeholder {
        width: 52px;
        height: 52px;
        background: #f1f5f9;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        font-size: 1.3rem;
    }

    .badge-cat {
        display: inline-block;
        padding: 0.28rem 0.75rem;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 600;
        letter-spacing: 0.03em;
    }
    .badge-fastfood  { background: #fff7e0; color: #b45309; }
    .badge-magasin   { background: #dbeafe; color: #1e40af; }
    .badge-cafeteria { background: #dcfce7; color: #15803d; }
    .badge-other     { background: #f1f5f9; color: #64748b; }

    .badge-stock {
        display: inline-block;
        padding: 0.25rem 0.65rem;
        border-radius: 20px;
        font-size: 0.82rem;
        font-weight: 700;
    }
    .stock-low    { background: #fee2e2; color: #b91c1c; }
    .stock-medium { background: #fff7e0; color: #b45309; }
    .stock-ok     { background: #dcfce7; color: #15803d; }

    .badge-promo-active   { background: #dcfce7; color: #15803d; font-size: 0.78rem; padding: 0.25rem 0.7rem; border-radius: 20px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; }
    .badge-promo-inactive { background: #f1f5f9; color: #64748b; font-size: 0.78rem; padding: 0.25rem 0.7rem; border-radius: 20px; font-weight: 600; display: inline-block; }

    .badge-expire-danger  { background: #fee2e2; color: #b91c1c; font-size: 0.78rem; padding: 0.25rem 0.65rem; border-radius: 20px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; }
    .badge-expire-warning { background: #fff7e0; color: #b45309; font-size: 0.78rem; padding: 0.25rem 0.65rem; border-radius: 20px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; }

    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: 1.5px solid;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        cursor: pointer;
        transition: background 0.15s, transform 0.12s;
        background: transparent;
        text-decoration: none;
    }
    .action-btn:hover { transform: translateY(-1px); }
    .action-btn-edit   { border-color: #3b82f6; color: #3b82f6; }
    .action-btn-edit:hover { background: #eff6ff; }
    .action-btn-promo  { border-color: #16a34a; color: #16a34a; }
    .action-btn-promo:hover { background: #f0fdf4; }
    .action-btn-delete { border-color: #ef4444; color: #ef4444; }
    .action-btn-delete:hover { background: #fef2f2; }

    .prix-normal { font-weight: 600; color: #1e293b; }
    .prix-promo  { font-size: 0.8rem; color: #16a34a; display: flex; align-items: center; gap: 3px; margin-top: 2px; }

    .empty-state {
        text-align: center;
        padding: 4rem 1rem;
        color: #94a3b8;
    }
    .empty-state i { font-size: 3rem; display: block; margin-bottom: 0.75rem; }

    /* Modal */
    .modal-content { border-radius: 16px; border: none; box-shadow: 0 8px 40px rgba(0,0,0,0.15); }
    .modal-header  { background: #f8fafc; border-radius: 16px 16px 0 0; border-bottom: 1.5px solid #e2e8f0; }
    .modal-footer  { border-top: 1.5px solid #e2e8f0; }
    /* ── Mode nuit ── */
    [data-theme="dark"] .catalogue-card {
        background: var(--bg-card);
    }
    [data-theme="dark"] .catalogue-table thead th {
        background: #1f2329;
        color: var(--text-muted);
        border-bottom-color: #444;
    }
    [data-theme="dark"] .catalogue-table tbody td {
        color: var(--text-main);
        border-bottom-color: #3a3f47;
    }
    [data-theme="dark"] .catalogue-table tbody tr:hover {
        background: #2d3139;
    }
    [data-theme="dark"] .search-bar,
    [data-theme="dark"] .filter-select {
        background: #2d3139;
        border-color: #444;
        color: var(--text-main);
    }
    [data-theme="dark"] .article-photo-placeholder {
        background: #2d3139;
        color: #6b7280;
    }
    [data-theme="dark"] td[style*="color:#64748b"],
    [data-theme="dark"] span[style*="color:#64748b"] {
        color: var(--text-muted) !important;
    }
    [data-theme="dark"] .prix-normal {
        color: var(--text-main);
    }
    [data-theme="dark"] .badge-other {
        background: #2d3139;
        color: var(--text-muted);
    }
    [data-theme="dark"] .badge-promo-inactive {
        background: #2d3139;
        color: var(--text-muted);
    }
    [data-theme="dark"] .modal-header {
        background: #1f2329;
        border-bottom-color: #444;
    }
</style>

<div class="catalogue-card">

    {{-- ── STAT CARDS ── --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <a href="{{ route('foyer.catalogue.index') }}" style="text-decoration:none;">
            <div class="stat-card stat-blue">
                <div class="stat-label"><i class="bi bi-box-seam"></i> Total Articles</div>
                <div class="stat-value">{{ $articles->count() }}</div>
                <i class="bi bi-box-seam stat-icon"></i>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-3">
        <a href="{{ route('foyer.catalogue.index', ['filtre' => 'promo']) }}" style="text-decoration:none;">
            <div class="stat-card stat-green">
                <div class="stat-label"><i class="bi bi-tag-fill"></i> En Promotion</div>
                <div class="stat-value">{{ $articles->where('promo_active', true)->count() }}</div>
                <i class="bi bi-tag-fill stat-icon"></i>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-3">
        <a href="{{ route('foyer.catalogue.index', ['filtre' => 'peremption']) }}" style="text-decoration:none;">
            <div class="stat-card stat-orange">
                <div class="stat-label"><i class="bi bi-clock-fill"></i> Périmés / Proches</div>
                <div class="stat-value">{{ $articles->filter(function($a){ return $a->date_peremption && $a->date_peremption <= now()->addDays(7); })->count() }}</div>
                <i class="bi bi-clock-fill stat-icon"></i>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-3">
        <a href="{{ route('foyer.catalogue.index', ['filtre' => 'stock_faible']) }}" style="text-decoration:none;">
            <div class="stat-card stat-red">
                <div class="stat-label"><i class="bi bi-exclamation-triangle-fill"></i> Stock Faible</div>
                <div class="stat-value">{{ $articles->where('stock', '<=', 5)->count() }}</div>
                <i class="bi bi-exclamation-triangle-fill stat-icon"></i>
            </div>
        </a>
    </div>
</div>

    {{-- ── SEARCH & FILTER ── --}}
    <div class="row g-3 mb-4 align-items-center">
        <div class="col-md-5">
            <input type="text"
                   id="searchInput"
                   class="form-control search-bar"
                   placeholder="🔍 Rechercher un article...">
        </div>
        <div class="col-md-3">
            <select id="categorieFilter" class="form-select filter-select">
                <option value="">Toutes les catégories</option>
                <option value="fastfood">Fast Food</option>
                <option value="magasin">Magasin</option>
                <option value="cafeteria">Cafétéria</option>
            </select>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('foyer.catalogue.create') }}" class="btn-add-article">
                <i class="bi bi-plus-lg"></i> Ajouter un article
            </a>
        </div>
    </div>
    @if(isset($filtre) && $filtre)
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem;">
        <span style="background:#fee2e2; color:#b91c1c; padding:0.4rem 1rem; border-radius:20px; font-size:0.85rem; font-weight:600;">
            <i class="bi bi-funnel-fill me-1"></i>
            @if($filtre === 'stock_faible') Stock Faible (≤ 5)
            @elseif($filtre === 'promo') En Promotion
            @elseif($filtre === 'peremption') Périmés / Proches de péremption
            @endif
        </span>
        <a href="{{ route('foyer.catalogue.index') }}"
           style="font-size:0.85rem; color:#64748b; text-decoration:none;">
            <i class="bi bi-x-circle me-1"></i> Voir tout
        </a>
    </div>
@endif
    {{-- ── TABLE ── --}}
    <div class="table-responsive">
        <table class="catalogue-table">
            <thead>
                <tr>
                    <th style="width:70px;">Photo</th>
                    <th>Article</th>
                    <th>Catégorie</th>
                    <th>Description</th>
                    <th>Prix</th>
                    <th>Stock</th>
                    <th>Péremption</th>
                    <th>Promo</th>
                    <th style="width:110px;">Actions</th>
                </tr>
            </thead>
            <tbody>

            @forelse($articles as $article)
                <tr class="article-row"
                    data-nom="{{ strtolower($article->nom_article) }}"
                    data-categorie="{{ strtolower($article->categorie) }}">

                    {{-- Photo --}}
                    <td>
                        @if($article->photo)
                            <img src="{{ asset('storage/' . $article->photo) }}"
                                 alt="{{ $article->nom_article }}"
                                 class="article-photo">
                        @else
                            <div class="article-photo-placeholder">
                                <i class="bi bi-image"></i>
                            </div>
                        @endif
                    </td>

                    {{-- Nom --}}
                    <td style="font-weight:600;">{{ $article->nom_article }}</td>

                    {{-- Catégorie --}}
                    <td>
                        @if($article->categorie == 'fastfood')
                            <span class="badge-cat badge-fastfood">Fast Food</span>
                        @elseif($article->categorie == 'magasin')
                            <span class="badge-cat badge-magasin">Magasin</span>
                        @elseif($article->categorie == 'cafeteria')
                            <span class="badge-cat badge-cafeteria">Cafétéria</span>
                        @else
                            <span class="badge-cat badge-other">Non définie</span>
                        @endif
                    </td>

                    {{-- Description --}}
                    <td style="color:#64748b; font-size:0.88rem;">
                        {{ $article->description ? Str::limit($article->description, 40) : '—' }}
                    </td>

                    {{-- Prix --}}
                    <td>
                        <div class="prix-normal">{{ number_format($article->prix, 2) }} DA</div>
                        @if($article->promo_active && $article->prix_promo)
                            <div class="prix-promo">
                                <i class="bi bi-tag-fill" style="font-size:0.75rem;"></i>
                                {{ number_format($article->prix_promo, 2) }} DA
                            </div>
                        @endif
                    </td>

                    {{-- Stock --}}
                    <td>
                        @if($article->stock <= 5)
                            <span class="badge-stock stock-low">{{ $article->stock }}</span>
                        @elseif($article->stock <= 10)
                            <span class="badge-stock stock-medium">{{ $article->stock }}</span>
                        @else
                            <span class="badge-stock stock-ok">{{ $article->stock }}</span>
                        @endif
                    </td>

                    {{-- Péremption --}}
                    <td>
                        @if($article->date_peremption)
                            @php
                                $daysLeft = now()->diffInDays(\Carbon\Carbon::parse($article->date_peremption), false);
                            @endphp
                            @if($daysLeft < 0)
                                <span class="badge-expire-danger">
                                    <i class="bi bi-exclamation-circle"></i> Périmé
                                </span>
                            @elseif($daysLeft <= 7)
                                <span class="badge-expire-warning">
                                    <i class="bi bi-clock"></i> {{ $daysLeft }}j
                                </span>
                            @else
                                <span style="font-size:0.85rem; color:#64748b;">
                                    {{ \Carbon\Carbon::parse($article->date_peremption)->format('d/m/Y') }}
                                </span>
                            @endif
                        @else
                            <span style="color:#cbd5e1;">—</span>
                        @endif
                    </td>

                    {{-- Promo --}}
                    <td>
                        @if($article->promo_active)
                            <span class="badge-promo-active">
                                <i class="bi bi-star-fill" style="font-size:0.7rem;"></i> Active
                            </span>
                        @else
                            <span class="badge-promo-inactive">Inactive</span>
                        @endif
                    </td>

                    {{-- Actions --}}
                    <td>
                        <div style="display:flex; gap:6px; align-items:center;">
                            <a href="{{ route('foyer.catalogue.edit', $article->id) }}"
                               class="action-btn action-btn-edit"
                               title="Modifier">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <button type="button"
                                    class="action-btn action-btn-promo"
                                    data-bs-toggle="modal"
                                    data-bs-target="#promoModal"
                                    onclick="openPromoModal({{ $article->id }}, '{{ $article->nom_article }}', {{ $article->prix }}, {{ $article->promo_active ? 'true' : 'false' }}, {{ $article->prix_promo ?? 'null' }}, '{{ $article->promo_remarque ?? '' }}', {{ $article->promo_qte_lot ?? 'null' }}, {{ $article->promo_prix_lot ?? 'null' }})"
                                    title="Promotion">
                                <i class="bi bi-tag"></i>
                            </button>

                            <form method="POST"
                                  action="{{ route('foyer.catalogue.destroy', $article) }}"
                                  style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="action-btn action-btn-delete"
                                        title="Supprimer"
                                        onclick="return confirm('Supprimer cet article ?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="9">
                        <div class="empty-state">
                            <i class="bi bi-box-seam"></i>
                            Aucun article dans le catalogue.
                        </div>
                    </td>
                </tr>
            @endforelse

            </tbody>
        </table>
    </div>

</div>

{{-- ── MODAL PROMOTION ── --}}
<div class="modal fade" id="promoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-star-fill text-warning me-2"></i>
                    Gérer la Promotion
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="promoForm" method="POST">
    @csrf
    <input type="hidden" name="promo_active" value="0">
                <div class="modal-body">
                    {{-- Article name --}}
                    <p class="mb-3">
                        <span class="text-muted">Article :</span>
                        <strong id="modalArticleName"></strong>
                    </p>

                    {{-- Prix normal (readonly) --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Prix normal (DA)</label>
                        <input type="number" id="prixNormal" class="form-control" readonly>
                    </div>

                    {{-- Toggle promo --}}
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="promoActiveCheck" name="promo_active" value="1">
                        <label class="form-check-label fw-semibold" for="promoActiveCheck">Activer la promotion</label>
                    </div>

                    <div id="promoFields" style="display:none;">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Prix promotionnel (DA)</label>
                            <input type="number" id="prixPromo" name="prix_promo" class="form-control" step="0.01" min="0">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Économie :</label>
                            <span id="savings" class="text-success fw-bold">0.00 DA</span>
                        </div>
                        <div class="mb-3">
    <label class="form-label fw-semibold">Remarque <span class="text-muted fw-normal small">(ex: 2 avec 90)</span></label>
    <input type="text" id="promoRemarque" name="promo_remarque" class="form-control" placeholder="Ex : 2 avec 90">
</div>

{{-- Offre lot --}}
<div class="mb-2">
    <label class="form-label fw-semibold">Offre lot <span class="text-muted fw-normal small">(optionnel)</span></label>
    <div class="d-flex gap-2">
        <input type="number" id="promoQteLot" name="promo_qte_lot" class="form-control"
               placeholder="Qté (ex: 2)" min="1" style="width:120px;">
        <input type="number" id="promoPrixLot" name="promo_prix_lot" class="form-control"
               placeholder="Prix total (ex: 90)" min="0" step="0.01">
    </div>
    <div class="small text-muted mt-1">Si l'étudiante prend exactement cette quantité, ce prix s'applique automatiquement.</div>
</div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle me-1"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const searchInput    = document.getElementById('searchInput');
    const categorieFilter = document.getElementById('categorieFilter');

    function filtrer() {
        const recherche = searchInput.value.toLowerCase();
        const categorie = categorieFilter.value.toLowerCase();
        document.querySelectorAll('.article-row').forEach(row => {
            const matchNom = row.dataset.nom.includes(recherche);
            const matchCat = categorie === '' || row.dataset.categorie === categorie;
            row.style.display = (matchNom && matchCat) ? '' : 'none';
        });
    }

    searchInput.addEventListener('keyup', filtrer);
    categorieFilter.addEventListener('change', filtrer);

    const promoActiveCheck = document.getElementById('promoActiveCheck');
    const promoFields      = document.getElementById('promoFields');
    const prixPromoInput   = document.getElementById('prixPromo');
    const prixNormalInput  = document.getElementById('prixNormal');

    promoActiveCheck.addEventListener('change', function () {
        promoFields.style.display = this.checked ? 'block' : 'none';
    });

    prixPromoInput.addEventListener('input', function () {
        const normal = parseFloat(prixNormalInput.value) || 0;
        const promo  = parseFloat(this.value) || 0;
        document.getElementById('savings').textContent = (normal - promo).toFixed(2) + ' DA';
    });
});

function openPromoModal(articleId, articleName, prixNormal, promoActive, prixPromo, remarque, qteLot, prixLot) {
    document.getElementById('modalArticleName').textContent = articleName;
    document.getElementById('prixNormal').value = prixNormal.toFixed(2);

    const promoActiveCheck = document.getElementById('promoActiveCheck');
    const promoFields      = document.getElementById('promoFields');
    const prixPromoInput   = document.getElementById('prixPromo');
    const remarqueInput    = document.getElementById('promoRemarque');
    const promoForm        = document.getElementById('promoForm');

    promoActiveCheck.checked  = promoActive;
    prixPromoInput.value      = prixPromo || '';
    remarqueInput.value       = remarque  || '';
    document.getElementById('promoQteLot').value  = qteLot  || '';
    document.getElementById('promoPrixLot').value = prixLot || '';
    promoFields.style.display = promoActive ? 'block' : 'none';
    promoForm.action          = `/foyer/catalogue/${articleId}/update-promo`;

    if (prixPromo) {
        document.getElementById('savings').textContent = (prixNormal - prixPromo).toFixed(2) + ' DA';
    }
}
</script>
@endsection