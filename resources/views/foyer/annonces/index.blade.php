@extends('layouts.app')

@section('title', 'Annonces')
@section('page-title', 'Gestion des Annonces')

@section('sidebar')
    @include('foyer.partials._sidebar')
@endsection

@section('content')


{{-- Toolbar --}}
<div class="card mb-4">
    <div class="card-body py-3">
        <div class="d-flex flex-wrap align-items-center gap-3">

            <input type="text" id="annSearch" class="form-control"
                   style="max-width:320px;"
                   placeholder="Rechercher une annonce..."
                   oninput="annFilter()">

            <select id="annCatFilter" class="form-select" style="max-width:200px;" onchange="annFilter()">
                <option value="all">Toutes les catégories</option>
                <option value="generale">Générale</option>
                <option value="promotion">Promotion</option>
            </select>

            <span class="text-muted" style="font-size:.85rem;">
                {{ $annonces->total() }} annonce(s)
            </span>

            <a href="{{ route('foyer.annonces.create') }}"
               class="btn btn-primary ms-auto">
                <i class="bi bi-plus-lg me-1"></i>Nouvelle annonce
            </a>
        </div>
    </div>
</div>

{{-- Liste --}}
<div class="d-flex flex-column gap-3">
@forelse($annonces as $annonce)
    @php
        $catLabels = [
            'generale'  => ['label' => 'Générale',  'bg' => 'bg-secondary'],
            'promotion' => ['label' => 'Promotion',  'bg' => 'bg-info text-dark'],
        ];
        $cat = $catLabels[$annonce->categorie] ?? $catLabels['generale'];

        $urgBadge = match($annonce->urgence ?? 'general') {
            'urgent' => '<span class="badge bg-danger"><i class="bi bi-circle-fill me-1" style="font-size:.6rem;"></i>Urgent</span>',
            default  => '',
        };
    @endphp

    <div class="card shadow-sm ann-item"
         data-categorie="{{ $annonce->categorie }}"
         data-titre="{{ strtolower($annonce->titre) }}"
         data-contenu="{{ strtolower(Str::limit($annonce->contenu, 300)) }}">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start gap-3">

                <div style="flex:1;">
                    {{-- Badges --}}
                    <div class="d-flex flex-wrap gap-2 mb-2">
                        <span class="badge {{ $cat['bg'] }}">{{ $cat['label'] }}</span>
                        {!! $urgBadge !!}
                    </div>

                    {{-- Titre --}}
                    <div class="fw-bold mb-1" style="font-size:1rem;">
                        {{ $annonce->titre }}
                    </div>

                    {{-- Contenu --}}
                    <div class="text-muted mb-2" style="font-size:.88rem; line-height:1.5;">
                        {{ Str::limit($annonce->contenu, 200) }}
                    </div>

                    {{-- Meta --}}
                    <div class="d-flex flex-wrap gap-3 text-muted" style="font-size:.78rem;">
                        <span><i class="bi bi-person me-1"></i>{{ $annonce->user->name ?? 'Foyer' }}</span>
                        <span><i class="bi bi-clock me-1"></i>{{ $annonce->created_at->diffForHumans() }}</span>
                        <span><i class="bi bi-send me-1"></i>{{ $annonce->destinataire }}</span>
                        @if($annonce->publiee)
                            <span class="text-success fw-semibold">● Publiée</span>
                        @else
                            <span class="text-muted">○ Non publiée</span>
                        @endif
                    </div>
                </div>

                {{-- Actions --}}
                <div class="d-flex gap-2 flex-shrink-0">
                    <a href="{{ route('foyer.annonces.edit', $annonce) }}"
                       class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-pencil me-1"></i>Modifier
                    </a>
                    <form method="POST"
                          action="{{ route('foyer.annonces.destroy', $annonce) }}"
                          onsubmit="return confirm('Supprimer cette annonce ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-trash me-1"></i>Supprimer
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>

@empty
    <div class="card">
        <div class="card-body text-center text-muted py-5">
            <i class="bi bi-megaphone fs-1 opacity-25"></i>
            <p class="mt-2 mb-3">Aucune annonce publiée pour l'instant.</p>
            <a href="{{ route('foyer.annonces.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>Créer la première annonce
            </a>
        </div>
    </div>
@endforelse

<div id="ann-no-result" style="display:none;">
    <div class="card">
        <div class="card-body text-center text-muted py-5">
            <i class="bi bi-search fs-1 opacity-25"></i>
            <p class="mt-2 mb-0">Aucune annonce ne correspond à votre recherche.</p>
        </div>
    </div>
</div>

</div>

@if($annonces->hasPages())
    <div class="mt-3">{{ $annonces->links() }}</div>
@endif

@endsection

@section('scripts')
<script>
function annFilter() {
    const q   = document.getElementById('annSearch').value.toLowerCase().trim();
    const cat = document.getElementById('annCatFilter').value;
    const items = document.querySelectorAll('.ann-item');
    let visible = 0;

    items.forEach(item => {
        const matchCat  = cat === 'all' || item.dataset.categorie === cat;
        const matchText = !q || item.dataset.titre.includes(q) || item.dataset.contenu.includes(q);
        const show = matchCat && matchText;
        item.style.display = show ? '' : 'none';
        if (show) visible++;
    });

    document.getElementById('ann-no-result').style.display =
        visible === 0 && items.length > 0 ? 'block' : 'none';
}
</script>
@endsection