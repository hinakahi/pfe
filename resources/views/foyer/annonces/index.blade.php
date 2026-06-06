{{-- resources/views/foyer/annonces/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Annonces')
@section('page-title', 'Gestion des Annonces')

@section('sidebar')
    @include('foyer.partials._sidebar')
@endsection

@section('content')

<style>
    .ann-wrap {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.07);
        overflow: hidden;
        border: none;
    }

    /* ── Barre du haut ── */
    .ann-toolbar {
        padding: 1.2rem 1.5rem;
        border-bottom: 1.5px solid #f1f5f9;
        background: #f8fafc;
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    /* Recherche */
    .ann-search-wrap {
        position: relative;
        flex: 1;
        min-width: 200px;
    }
    .ann-search-wrap i {
        position: absolute;
        left: 11px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 1rem;
        pointer-events: none;
    }
    .ann-search-wrap input {
        width: 100%;
        padding: 0.5rem 0.9rem 0.5rem 2.2rem;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        font-size: 0.88rem;
        background: #fff;
        color: #1e293b;
        outline: none;
        transition: border-color 0.15s;
        box-sizing: border-box;
    }
    .ann-search-wrap input:focus { border-color: #2979d8; }

    /* Dropdown filtre */
    .ann-filter-wrap { position: relative; }
    .ann-filter-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 0.5rem 1rem;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        background: #fff;
        font-size: 0.88rem;
        font-weight: 500;
        color: #475569;
        cursor: pointer;
        transition: all 0.15s;
        white-space: nowrap;
    }
    .ann-filter-btn:hover { border-color: #94a3b8; background: #f8fafc; }
    .ann-filter-btn.f-urgente   { background: #fef2f2; color: #b91c1c; border-color: #fca5a5; }
    .ann-filter-btn.f-generale  { background: #eff6ff; color: #1a4fa0; border-color: #93c5fd; }
    .ann-filter-btn.f-evenement { background: #f0fdf4; color: #15803d; border-color: #86efac; }

    .ann-dropdown {
        display: none;
        position: absolute;
        right: 0;
        top: calc(100% + 6px);
        background: #fff;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        min-width: 170px;
        z-index: 100;
        overflow: hidden;
        box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    }
    .ann-dropdown.open { display: block; }
    .ann-dropdown button {
        display: flex;
        align-items: center;
        gap: 8px;
        width: 100%;
        padding: 0.65rem 1rem;
        background: none;
        border: none;
        font-size: 0.85rem;
        color: #334155;
        cursor: pointer;
        text-align: left;
    }
    .ann-dropdown button:hover { background: #f8fafc; }

    /* Bouton Nouvelle annonce */
    .btn-new {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 0.5rem 1.2rem;
        border-radius: 10px;
        background: linear-gradient(135deg, #1a4fa0, #2979d8);
        color: #fff;
        font-size: 0.88rem;
        font-weight: 600;
        text-decoration: none;
        border: none;
        white-space: nowrap;
        transition: opacity 0.15s;
    }
    .btn-new:hover { opacity: 0.88; color: #fff; }

    /* ── Items ── */
    .ann-item {
        padding: 1.2rem 1.5rem;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1rem;
        transition: background 0.15s;
    }
    .ann-item:last-child { border-bottom: none; }
    .ann-item:hover { background: #f8fafc; }
    .ann-item.hidden { display: none; }

    /* Badge catégorie */
    .cat-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    .cat-generale  { background: #dbeafe; color: #1d4ed8; }
    .cat-urgente   { background: #fee2e2; color: #b91c1c; }
    .cat-evenement { background: #dcfce7; color: #15803d; }

    .ann-titre   { font-weight: 700; font-size: 1rem; color: #1e293b; margin-bottom: 0.3rem; }
    .ann-contenu { font-size: 0.88rem; color: #475569; line-height: 1.5; margin-bottom: 0.5rem; }
    .ann-meta    { font-size: 0.75rem; color: #94a3b8; display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }

    /* Boutons modifier / supprimer */
    .ann-actions { display: flex; gap: 8px; align-items: center; flex-shrink: 0; }

    .btn-edit {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 0.3rem 0.75rem;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 500;
        border: 1.5px solid #2979d8;
        color: #1a4fa0;
        background: #eff6ff;
        text-decoration: none;
        transition: all 0.15s;
    }
    .btn-edit:hover { background: #dbeafe; color: #1a4fa0; }

    .btn-delete {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 0.3rem 0.75rem;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 500;
        border: 1.5px solid #ef4444;
        color: #b91c1c;
        background: #fef2f2;
        cursor: pointer;
        transition: all 0.15s;
    }
    .btn-delete:hover { background: #fee2e2; }

    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 4rem 1rem;
        color: #94a3b8;
    }
    .empty-state i { font-size: 3rem; display: block; margin-bottom: 0.75rem; }

    /* Message aucun résultat (recherche) */
    #ann-no-result {
        display: none;
        text-align: center;
        padding: 3rem 1rem;
        color: #94a3b8;
        font-size: 0.9rem;
    }
</style>

{{-- Flash messages --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="ann-wrap">

    {{-- ── Toolbar ── --}}
    <div class="ann-toolbar">

        {{-- Recherche --}}
        <div class="ann-search-wrap">
            <i class="bi bi-search"></i>
            <input type="text"
                   id="annSearch"
                   placeholder="Rechercher une annonce..."
                   oninput="annFilter()">
        </div>

        {{-- Filtre catégorie --}}
        <div class="ann-filter-wrap">
            <button class="ann-filter-btn" id="annFilterBtn" onclick="annToggleDropdown()">
                <i class="bi bi-funnel" id="annFilterIcon"></i>
                <span id="annFilterLabel">Toutes</span>
                <i class="bi bi-chevron-down" style="font-size:0.75rem;"></i>
            </button>
            <div class="ann-dropdown" id="annDropdown">
                <button onclick="annSetFilter('all')">
                    <i class="bi bi-list-ul"></i> Toutes
                </button>
                <button onclick="annSetFilter('urgente')">
                    <i class="bi bi-exclamation-triangle-fill" style="color:#b91c1c;"></i> Urgentes
                </button>
                <button onclick="annSetFilter('generale')">
                    <i class="bi bi-info-circle-fill" style="color:#1d4ed8;"></i> Générales
                </button>
                <button onclick="annSetFilter('evenement')">
                    <i class="bi bi-calendar-event-fill" style="color:#15803d;"></i> Événements
                </button>
            </div>
        </div>

        {{-- Compteur --}}
        <span style="font-size:0.82rem; color:#94a3b8; white-space:nowrap;">
            {{ $annonces->total() }} annonce(s)
        </span>

        {{-- Nouvelle annonce --}}
        <a href="{{ route('foyer.annonces.create') }}" class="btn-new ms-auto">
            <i class="bi bi-plus-lg"></i> Nouvelle annonce
        </a>
    </div>

    {{-- ── Liste ── --}}
    @forelse($annonces as $annonce)
        @php
            $catInfo = match($annonce->categorie) {
                'urgente'   => ['bi-exclamation-triangle-fill', 'Urgente',   'urgente'],
                'evenement' => ['bi-calendar-event-fill',       'Événement', 'evenement'],
                default     => ['bi-info-circle-fill',          'Générale',  'generale'],
            };
        @endphp

        <div class="ann-item"
             data-categorie="{{ $annonce->categorie }}"
             data-titre="{{ strtolower($annonce->titre) }}"
             data-contenu="{{ strtolower(Str::limit($annonce->contenu, 300)) }}">

            <div style="flex:1;">
                <span class="cat-badge cat-{{ $catInfo[2] }}">
                    <i class="bi {{ $catInfo[0] }}"></i> {{ $catInfo[1] }}
                </span>
                <div class="ann-titre">{{ $annonce->titre }}</div>
                <div class="ann-contenu">{{ Str::limit($annonce->contenu, 200) }}</div>
                <div class="ann-meta">
                    <span><i class="bi bi-person me-1"></i>{{ $annonce->user->name ?? 'Foyer' }}</span>
                    <span><i class="bi bi-clock me-1"></i>{{ $annonce->created_at->diffForHumans() }}</span>
                    <span><i class="bi bi-people me-1"></i>{{ $annonce->destinataire }}</span>
                </div>
            </div>

            <div class="ann-actions">
                {{-- Modifier --}}
                <a href="{{ route('foyer.annonces.edit', $annonce) }}" class="btn-edit">
                    <i class="bi bi-pencil"></i> Modifier
                </a>

                {{-- Supprimer --}}
                <form method="POST"
                      action="{{ route('foyer.annonces.destroy', $annonce) }}"
                      onsubmit="return confirm('Supprimer cette annonce ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-delete">
                        <i class="bi bi-trash"></i> Supprimer
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="empty-state">
            <i class="bi bi-megaphone"></i>
            Aucune annonce publiée pour l'instant.
            <div class="mt-3">
                <a href="{{ route('foyer.annonces.create') }}" class="btn-new">
                    <i class="bi bi-plus-lg"></i> Créer la première annonce
                </a>
            </div>
        </div>
    @endforelse

    {{-- Message aucun résultat côté JS --}}
    <div id="ann-no-result">
        <i class="bi bi-search" style="font-size:2rem; display:block; margin-bottom:0.5rem;"></i>
        Aucune annonce ne correspond à votre recherche.
    </div>

</div>

{{-- Pagination --}}
@if($annonces->hasPages())
    <div class="mt-3">{{ $annonces->links() }}</div>
@endif

@endsection

@section('scripts')
<script>
let annCurrentFilter = 'all';

function annToggleDropdown() {
    document.getElementById('annDropdown').classList.toggle('open');
}

document.addEventListener('click', function(e) {
    if (!e.target.closest('.ann-filter-wrap')) {
        document.getElementById('annDropdown').classList.remove('open');
    }
});

function annSetFilter(type) {
    annCurrentFilter = type;

    const labels = { all: 'Toutes', urgente: 'Urgentes', generale: 'Générales', evenement: 'Événements' };
    const icons  = { all: 'bi-funnel', urgente: 'bi-exclamation-triangle-fill', generale: 'bi-info-circle-fill', evenement: 'bi-calendar-event-fill' };
    const btnClasses = { urgente: 'f-urgente', generale: 'f-generale', evenement: 'f-evenement' };

    document.getElementById('annFilterLabel').textContent = labels[type];

    const btn = document.getElementById('annFilterBtn');
    btn.className = 'ann-filter-btn' + (btnClasses[type] ? ' ' + btnClasses[type] : '');

    document.getElementById('annFilterIcon').className = 'bi ' + (icons[type] || 'bi-funnel');
    document.getElementById('annDropdown').classList.remove('open');

    annFilter();
}

function annFilter() {
    const q = document.getElementById('annSearch').value.toLowerCase().trim();
    const items = document.querySelectorAll('.ann-item');
    let visible = 0;

    items.forEach(function(item) {
        const matchCat   = annCurrentFilter === 'all' || item.dataset.categorie === annCurrentFilter;
        const matchText  = !q || item.dataset.titre.includes(q) || item.dataset.contenu.includes(q);
        const show = matchCat && matchText;
        item.classList.toggle('hidden', !show);
        if (show) visible++;
    });

    document.getElementById('ann-no-result').style.display = visible === 0 && items.length > 0 ? 'block' : 'none';
}
</script>
@endsection