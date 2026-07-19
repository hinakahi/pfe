@extends('layouts.app')
@section('title', 'Annonces')

@section('sidebar')
    @include('admin.partials._sidebar')
@endsection

@section('page-title', 'Annonces générales')

@section('content')

{{-- Toolbar --}}
<div class="card mb-4">
    <div class="card-body py-3">
        <div class="d-flex flex-wrap align-items-center gap-3">

            <input type="text" id="annSearch" class="form-control"
                   style="max-width:320px;"
                   placeholder="Rechercher une annonce..."
                   oninput="annFilter()">

            <select id="annDestFilter" class="form-select" style="max-width:200px;" onchange="annFilter()">
                <option value="all">Tous les destinataires</option>
                <option value="tous">Tous</option>
                <option value="etudiantes">Étudiantes</option>
                <option value="staff">Staff</option>
            </select>

            <span class="text-muted" style="font-size:.85rem;">
                {{ $annonces->total() }} annonce(s)
            </span>

            <a href="{{ route('admin.annonces.create') }}" class="btn btn-primary ms-auto">
                <i class="bi bi-plus-lg me-1"></i>Nouvelle annonce
            </a>
        </div>
    </div>
</div>

{{-- Liste --}}
<div class="d-flex flex-column gap-3">
@forelse($annonces as $a)

    @php
        $urgBadge = match($a->urgence ?? 'general') {
            'urgent' => '<span class="badge bg-danger"><i class="bi bi-circle-fill me-1" style="font-size:.6rem;"></i>Urgent</span>',
            default  => '',
        };

        $destBadgeClass = match($a->destinataire) {
            'etudiantes' => 'bg-primary',
            'staff'      => 'bg-warning text-dark',
            default      => 'bg-info text-dark',
        };
    @endphp

    <div class="card shadow-sm ann-item"
         data-dest="{{ $a->destinataire }}"
         data-titre="{{ strtolower($a->titre) }}"
         data-contenu="{{ strtolower(Str::limit($a->contenu, 300)) }}">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start gap-3">

                <div style="flex:1;">
                    {{-- Badges --}}
                    <div class="d-flex flex-wrap gap-2 mb-2">
                        <span class="badge {{ $destBadgeClass }}">{{ $a->destinataire }}</span>
                        {!! $urgBadge !!}
                    </div>

                    {{-- Titre --}}
                    <div class="fw-bold mb-1" style="font-size:1rem;">
                        {{ $a->titre }}
                    </div>

                    {{-- Contenu --}}
                    <div class="text-muted mb-2" style="font-size:.88rem; line-height:1.5;">
                        {{ Str::limit($a->contenu, 200) }}
                    </div>

                    {{-- Meta --}}
                    <div class="d-flex flex-wrap gap-3 text-muted" style="font-size:.78rem;">
                        <span><i class="bi bi-person me-1"></i>{{ $a->user->name ?? '—' }}</span>
                        <span><i class="bi bi-clock me-1"></i>{{ $a->created_at->diffForHumans() }}</span>
                        <span><i class="bi bi-calendar me-1"></i>{{ $a->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="d-flex gap-2 flex-shrink-0">
                    <a href="{{ route('admin.annonces.edit', $a) }}"
                       class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-pencil me-1"></i>Modifier
                    </a>
                    <form method="POST"
                          action="{{ route('admin.annonces.destroy', $a) }}"
                          class="d-inline"
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
            <a href="{{ route('admin.annonces.create') }}" class="btn btn-primary">
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
    const q    = document.getElementById('annSearch').value.toLowerCase().trim();
    const dest = document.getElementById('annDestFilter').value;
    const items = document.querySelectorAll('.ann-item');
    let visible = 0;

    items.forEach(item => {
        const matchDest = dest === 'all' || item.dataset.dest === dest;
        const matchText = !q || item.dataset.titre.includes(q) || item.dataset.contenu.includes(q);
        const show = matchDest && matchText;
        item.style.display = show ? '' : 'none';
        if (show) visible++;
    });

    document.getElementById('ann-no-result').style.display =
        visible === 0 && items.length > 0 ? 'block' : 'none';
}
</script>
@endsection