@extends('layouts.app')
@section('page-title', 'Gestion des Chambres')


@section('content')

{{-- Titre --}}
<div class="mb-4">
    <h4 class="fw-bold mb-1">Gestion des chambres</h4>
    <p class="text-muted mb-0">Consulter, filtrer et gérer les affectations de chambres.</p>
</div>



{{-- Filtres + Actions --}}
<div class="card mb-4">
    <div class="card-body">
        <div class="row g-2 align-items-center">
            <div class="col-md-2">
                <select class="form-select form-select-sm" id="filtreType" onchange="filtrer()">
                    <option value="">Toutes catégories</option>
                    <option value="individuelle">Individuelle</option>
                    <option value="double">Double</option>
                </select>
            </div>
            <div class="col-md-2">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="filtreVide" onchange="filtrer()">
                    <label class="form-check-label" for="filtreVide">Vides uniquement</label>
                </div>
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control form-control-sm" id="filtreSearch"
                       placeholder="Rechercher n° chambre ou bloc..." onkeyup="filtrer()">
            </div>
            <div class="col-md-5 text-end d-flex gap-2 justify-content-end">
                <a href="{{ route('hebergement.chambres.import') }}" class="btn btn-sm btn-outline-success">
                    <i class="bi bi-file-earmark-excel me-1"></i> Importer Excel Chambres
                </a>
                <form method="POST" action="{{ route('hebergement.chambres.publier') }}" class="d-inline">
                    @csrf
                    <button class="btn btn-sm btn-primary">
                        <i class="bi bi-megaphone me-1"></i> Publier la liste des vides
                    </button>
                </form>
                <a href="{{ route('hebergement.chambres.create') }}" class="btn btn-sm btn-success">
                    <i class="bi bi-plus"></i>
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Tableau --}}
<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0" id="tableChambres">
            <thead class="table-light">
                <tr>
                    <th>Numéro</th>
                    <th>Type</th>
                    <th>Bloc</th>
                    <th>Étage</th>
                    <th>Capacité</th>
                    <th>Statut</th>
                    <th>Publiée</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="tbody">
                @forelse($chambres as $chambre)
                <tr data-type="{{ $chambre->type }}"
                    data-statut="{{ $chambre->statut }}"
                    data-search="{{ strtolower($chambre->numero . ' ' . $chambre->bloc) }}">
                    <td><strong>{{ $chambre->numero }}</strong></td>
                    <td>
                        @if($chambre->type === 'individuelle')
                            <span class="badge bg-info">Individuelle</span>
                        @else
                            <span class="badge bg-purple" style="background:#6f42c1">Double</span>
                        @endif
                    </td>
                    <td>{{ $chambre->bloc ?? '-' }}</td>
                    <td>{{ $chambre->etage }}</td>
                    <td>{{ $chambre->capacite }} pers.</td>
                    <td>
                        @if($chambre->statut === 'libre')
                            <span class="badge bg-success">Disponible</span>
                        @else
                            <span class="badge bg-danger">Occupée</span>
                        @endif
                    </td>
                    <td>
                        @if($chambre->publiee)
                            <span class="badge bg-primary">Oui</span>
                        @else
                            <span class="badge bg-secondary">Non</span>
                        @endif
                    </td>
                    <td>
                        <form method="POST" action="{{ route('hebergement.chambres.destroy', $chambre) }}"
                              onsubmit="return confirm('Supprimer cette chambre ?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr id="emptyRow">
                    <td colspan="8" class="text-center text-muted py-4">Aucune chambre enregistrée.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $chambres->links() }}</div>

@endsection

@section('scripts')
<script>
function filtrer() {
    const type    = document.getElementById('filtreType').value.toLowerCase();
    const vide    = document.getElementById('filtreVide').checked;
    const search  = document.getElementById('filtreSearch').value.toLowerCase();
    const rows    = document.querySelectorAll('#tbody tr[data-type]');
    let visible   = 0;

    rows.forEach(row => {
        const matchType   = !type   || row.dataset.type === type;
        const matchVide   = !vide   || row.dataset.statut === 'libre';
        const matchSearch = !search || row.dataset.search.includes(search);

        if (matchType && matchVide && matchSearch) {
            row.style.display = '';
            visible++;
        } else {
            row.style.display = 'none';
        }
    });
}
</script>
@endsection