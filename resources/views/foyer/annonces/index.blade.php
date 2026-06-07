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

    /* ── Toolbar ── */
    .ann-toolbar {
        padding: 1.2rem 1.5rem;
        border-bottom: 1.5px solid #f1f5f9;
        background: #f8fafc;
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .ann-search-wrap { position: relative; flex: 1; min-width: 200px; }
    .ann-search-wrap input {
        width: 100%;
        padding: 0.5rem 0.9rem 0.5rem 0.9rem;
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
        display: inline-flex; align-items: center; gap: 6px;
        padding: 0.5rem 1rem;
        border: 1.5px solid #e2e8f0; border-radius: 10px;
        background: #fff; font-size: 0.88rem; font-weight: 500;
        color: #475569; cursor: pointer; transition: all 0.15s; white-space: nowrap;
    }
    .ann-filter-btn:hover { border-color: #94a3b8; background: #f8fafc; }

    .ann-dropdown {
        display: none; position: absolute; right: 0; top: calc(100% + 6px);
        background: #fff; border: 1.5px solid #e2e8f0; border-radius: 12px;
        min-width: 190px; z-index: 100; overflow: hidden;
        box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    }
    .ann-dropdown.open { display: block; }
    .ann-dropdown button {
        display: flex; align-items: center; gap: 8px;
        width: 100%; padding: 0.65rem 1rem;
        background: none; border: none; font-size: 0.85rem;
        color: #334155; cursor: pointer; text-align: left;
    }
    .ann-dropdown button:hover { background: #f8fafc; }

    .btn-new {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 0.5rem 1.2rem; border-radius: 10px;
        background: linear-gradient(135deg, #1a4fa0, #2979d8);
        color: #fff; font-size: 0.88rem; font-weight: 600;
        text-decoration: none; border: none; white-space: nowrap;
        transition: opacity 0.15s;
    }
    .btn-new:hover { opacity: 0.88; color: #fff; }

    /* ── Items ── */
    .ann-item {
        padding: 1.2rem 1.5rem;
        border-bottom: 1px solid #f1f5f9;
        display: flex; justify-content: space-between;
        align-items: flex-start; gap: 1rem;
        transition: background 0.15s;
    }
    .ann-item:last-child { border-bottom: none; }
    .ann-item:hover { background: #f8fafc; }
    .ann-item.hidden { display: none; }

    /* Badges */
    .badge-cat {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 0.22rem 0.7rem; border-radius: 20px;
        font-size: 0.75rem; font-weight: 600; margin-right: 4px;
    }
    .badge-generale    { background: #f1f5f9; color: #475569; }
    .badge-hebergement { background: #dbeafe; color: #1d4ed8; }
    .badge-foyer       { background: #dcfce7; color: #15803d; }
    .badge-maintenance { background: #ffedd5; color: #c2410c; }
    .badge-promotion   { background: #fee2e2; color: #b91c1c; }

    .badge-urg-general        { background: #f1f5f9; color: #475569; }
    .badge-urg-urgent         { background: #fee2e2; color: #b91c1c; }
    .badge-urg-administration { background: #1e293b; color: #fff; }

    .ann-titre   { font-weight: 700; font-size: 1rem; color: #1e293b; margin-bottom: 0.3rem; }
    .ann-contenu { font-size: 0.88rem; color: #475569; line-height: 1.5; margin-bottom: 0.5rem; }
    .ann-meta    { font-size: 0.75rem; color: #94a3b8; display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }

    .ann-actions { display: flex; gap: 8px; align-items: center; flex-shrink: 0; }

    .btn-edit {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 0.3rem 0.75rem; border-radius: 8px; font-size: 0.8rem; font-weight: 500;
        border: 1.5px solid #2979d8; color: #1a4fa0; background: #eff6ff;
        text-decoration: none; transition: all 0.15s;
    }
    .btn-edit:hover { background: #dbeafe; color: #1a4fa0; }

    .btn-delete {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 0.3rem 0.75rem; border-radius: 8px; font-size: 0.8rem; font-weight: 500;
        border: 1.5px solid #ef4444; color: #b91c1c; background: #fef2f2;
        cursor: pointer; transition: all 0.15s;
    }
    .btn-delete:hover { background: #fee2e2; }

    .empty-state { text-align: center; padding: 4rem 1rem; color: #94a3b8; }
    .empty-state i { font-size: 3rem; display: block; margin-bottom: 0.75rem; }

    #ann-no-result {
        display: none; text-align: center; padding: 3rem 1rem;
        color: #94a3b8; font-size: 0.9rem;
    }
</style>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="ann-wrap">

    {{-- Toolbar --}}
    <div class="ann-toolbar">

        {{-- Recherche --}}
        <div class="ann-search-wrap">
            <input type="text" id="annSearch"
                   placeholder="Rechercher une annonce..."
                   oninput="annFilter()">
        </div>

        {{-- Filtre catégorie --}}
        <div class="ann-filter-wrap">
            <button class="ann-filter-btn" id="annFilterBtn" onclick="annToggleDropdown()">
                <span id="annFilterLabel">Toutes les catégories</span>
                <i class="bi bi-chevron-down" style="font-size:0.75rem;"></i>
            </button>
            <div class="ann-dropdown" id="annDropdown">
                <button onclick="annSetFilter('all')">Toutes</button>
                <button onclick="annSetFilter('generale')"> Générale</button>
               
                <button onclick="annSetFilter('promotion')">Promotion</button>
            </div>
        </div>

        {{-- Compteur --}}
        <span style="font-size:0.82rem; color:#94a3b8; white-space:nowrap;">
            {{ $annonces->total() }} annonce(s)
        </span>

        {{-- Nouvelle annonce --}}
        <a href="{{ route('foyer.annonces.create') }}" class="btn-new ms-auto">
            + Nouvelle annonce
        </a>
    </div>

    {{-- Liste --}}
    @forelse($annonces as $annonce)
        @php
            $catLabels = [
                'generale'    => ['label' => 'Générale',    'class' => 'badge-generale'],
                'hebergement' => ['label' => 'Hébergement', 'class' => 'badge-hebergement'],
                'foyer'       => ['label' => 'Foyer',       'class' => 'badge-foyer'],
                'maintenance' => ['label' => 'Maintenance', 'class' => 'badge-maintenance'],
                'promotion'   => ['label' => 'Promotion',   'class' => 'badge-promotion'],
            ];
            $cat = $catLabels[$annonce->categorie] ?? $catLabels['generale'];

            $urgLabels = [
                'general'        => ['label' => 'Général',        'class' => 'badge-urg-general'],
                'urgent'         => ['label' => '🔴 Urgent',      'class' => 'badge-urg-urgent'],
                'administration' => ['label' => '👔 Administration', 'class' => 'badge-urg-administration'],
            ];
            $urg = $urgLabels[$annonce->urgence ?? 'general'] ?? $urgLabels['general'];
        @endphp

        <div class="ann-item"
             data-categorie="{{ $annonce->categorie }}"
             data-titre="{{ strtolower($annonce->titre) }}"
             data-contenu="{{ strtolower(Str::limit($annonce->contenu, 300)) }}">

            <div style="flex:1;">
                <div style="margin-bottom: 0.5rem;">
                    <span class="badge-cat {{ $cat['class'] }}">{{ $cat['label'] }}</span>
                    @if($annonce->urgence !== 'general')
                        <span class="badge-cat {{ $urg['class'] }}">{{ $urg['label'] }}</span>
                    @endif
                </div>
                <div class="ann-titre">{{ $annonce->titre }}</div>
                <div class="ann-contenu">{{ Str::limit($annonce->contenu, 200) }}</div>
                <div class="ann-meta">
                    <span>{{ $annonce->user->name ?? 'Foyer' }}</span>
                    <span>{{ $annonce->created_at->diffForHumans() }}</span>
                    <span>→ {{ $annonce->destinataire }}</span>
                    @if($annonce->publiee)
                        <span style="color: #15803d; font-weight: 600;">● Publiée</span>
                    @else
                        <span style="color: #94a3b8; font-weight: 600;">○ Non publiée</span>
                    @endif
                </div>
            </div>

            <div class="ann-actions">
                <a href="{{ route('foyer.annonces.edit', $annonce) }}" class="btn-edit">
                    Modifier
                </a>
                <form method="POST"
                      action="{{ route('foyer.annonces.destroy', $annonce) }}"
                      onsubmit="return confirm('Supprimer cette annonce ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-delete">Supprimer</button>
                </form>
            </div>
        </div>
    @empty
        <div class="empty-state">
            <i class="bi bi-megaphone"></i>
            Aucune annonce publiée pour l'instant.
            <div class="mt-3">
                <a href="{{ route('foyer.annonces.create') }}" class="btn-new">
                    + Créer la première annonce
                </a>
            </div>
        </div>
    @endforelse

    <div id="ann-no-result">
        Aucune annonce ne correspond à votre recherche.
    </div>

</div>

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

const filterLabels = {
    all: 'Toutes les catégories',
    generale: '📢 Générale',
    hebergement: '🏠 Hébergement',
    foyer: '🏘️ Foyer',
    maintenance: '🔧 Maintenance',
    promotion: '🎉 Promotion',
};

function annSetFilter(type) {
    annCurrentFilter = type;
    document.getElementById('annFilterLabel').textContent = filterLabels[type];
    document.getElementById('annDropdown').classList.remove('open');
    annFilter();
}

function annFilter() {
    const q = document.getElementById('annSearch').value.toLowerCase().trim();
    const items = document.querySelectorAll('.ann-item');
    let visible = 0;

    items.forEach(function(item) {
        const matchCat  = annCurrentFilter === 'all' || item.dataset.categorie === annCurrentFilter;
        const matchText = !q || item.dataset.titre.includes(q) || item.dataset.contenu.includes(q);
        const show = matchCat && matchText;
        item.classList.toggle('hidden', !show);
        if (show) visible++;
    });

    document.getElementById('ann-no-result').style.display =
        visible === 0 && items.length > 0 ? 'block' : 'none';
}
</script>
@endsection