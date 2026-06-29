@extends('layouts.app')
@section('page-title', 'Gestion des Chambres')

@section('content')

{{-- Stats cliquables --}}
<div class="row mb-4">
    <div class="col-md-3">
        <a href="{{ route('hebergement.chambres.index', array_filter(array_merge(request()->query(), ['statut' => null]))) }}"
           class="text-decoration-none">
            <div class="stat-card {{ !request('statut') ? 'stat-card-active' : '' }}"
                 style="background: linear-gradient(135deg,#1a3c5e,#2d6a9f)">
                <div class="number">{{ $stats['total'] }}</div>
                <div class="label"><i class="bi bi-door-open me-1"></i>Total chambres</div>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="{{ route('hebergement.chambres.index', array_merge(request()->query(), ['statut' => 'libre'])) }}"
           class="text-decoration-none">
            <div class="stat-card {{ request('statut') == 'libre' ? 'stat-card-active' : '' }}"
                 style="background: linear-gradient(135deg,#198754,#20c997)">
                <div class="number">{{ $stats['disponibles'] }}</div>
                <div class="label"><i class="bi bi-check-circle me-1"></i>Disponibles</div>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="{{ route('hebergement.chambres.index', array_merge(request()->query(), ['statut' => 'occupee'])) }}"
           class="text-decoration-none">
            <div class="stat-card {{ request('statut') == 'occupee' ? 'stat-card-active' : '' }}"
                 style="background: linear-gradient(135deg,#fd7e14,#ffc107)">
                <div class="number">{{ $stats['occupees'] }}</div>
                <div class="label"><i class="bi bi-person-fill me-1"></i>Occupées</div>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="{{ route('hebergement.chambres.index', array_merge(request()->query(), ['statut' => 'partielle'])) }}"
           class="text-decoration-none">
            <div class="stat-card {{ request('statut') == 'partielle' ? 'stat-card-active' : '' }}"
                 style="background: linear-gradient(135deg,#dc3545,#e91e63)">
                <div class="number">{{ $stats['une_place'] }}</div>
                <div class="label"><i class="bi bi-exclamation-circle me-1"></i>1 place libre</div>
            </div>
        </a>
    </div>
</div>

<style>
.stat-card-active {
    box-shadow: 0 0 0 3px #fff, 0 0 0 5px rgba(0,0,0,0.3);
}
</style>

{{-- Filtres + Actions --}}
<div class="card mb-4">
    <div class="card-body">
        <div class="row g-2 align-items-center">

            {{-- Formulaire GET filtres --}}
            <form method="GET" action="{{ route('hebergement.chambres.index') }}" id="filterForm"
                  class="col-md-7 d-flex gap-2 align-items-center p-0">
                  <input type="hidden" name="statut" value="{{ request('statut') }}">
                <select class="form-select form-select-sm" name="categorie" onchange="this.form.submit()">
                    <option value="">Toutes catégories</option>
                    <option value="individuelle" {{ request('categorie') == 'individuelle' ? 'selected' : '' }}>Individuelle</option>
                    <option value="double" {{ request('categorie') == 'double' ? 'selected' : '' }}>Double</option>
                </select>

                <div class="form-check text-nowrap ms-2">
                    <input class="form-check-input" type="checkbox" name="vides" value="1"
                           id="filtreVide" {{ request('vides') ? 'checked' : '' }}
                           onchange="this.form.submit()">
                    <label class="form-check-label" for="filtreVide">Vides uniquement</label>
                </div>

                <input type="text" class="form-control form-control-sm" name="search"
                       value="{{ request('search') }}"
                       placeholder="Rechercher n° chambre ou bloc..."
                       onkeyup="if(event.key==='Enter') this.form.submit()">
            </form>

            {{-- Boutons actions --}}
            <div class="col-md-5 text-end d-flex gap-2 justify-content-end">
                <a href="{{ route('hebergement.chambres.import') }}" class="btn btn-sm btn-outline-success">
                    <i class="bi bi-file-earmark-excel me-1"></i> Importer Excel
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
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Numéro</th>
                    <th>Type</th>
                    <th>Bloc</th>
                    <th>Étage</th>
                    <th>Étudiante 1</th>
                    <th>Étudiante 2</th>
                    <th>Statut</th>
                    <th>Publiée</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($chambres as $chambre)
                <tr>
                    <td><strong>{{ $chambre->numero }}</strong></td>

                    <td>
                        @if($chambre->type === 'individuelle')
                            <span class="badge bg-info text-dark">Individuelle</span>
                        @else
                            <span class="badge" style="background:#6f42c1">Double</span>
                        @endif
                    </td>

                    <td>{{ $chambre->bloc ?? '-' }}</td>
                    <td>{{ $chambre->etage }}</td>

                    {{-- Étudiante 1 --}}
                    <td>
                        @if($chambre->etudiante_1)
                            <span class="d-flex align-items-center gap-2">
                                <span class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center"
                                      style="width:28px;height:28px;font-size:11px">
                                    {{ strtoupper(substr($chambre->etudiante_1, 0, 2)) }}
                                </span>
                                {{ $chambre->etudiante_1 }}
                            </span>
                        @else
                            <span class="text-muted fst-italic small">Place libre</span>
                        @endif
                    </td>

                    {{-- Étudiante 2 --}}
                    <td>
                        @if($chambre->type === 'double')
                            @if($chambre->etudiante_2)
                                <span class="d-flex align-items-center gap-2">
                                    <span class="rounded-circle text-white d-inline-flex align-items-center justify-content-center"
                                          style="width:28px;height:28px;font-size:11px;background:#6f42c1">
                                        {{ strtoupper(substr($chambre->etudiante_2, 0, 2)) }}
                                    </span>
                                    {{ $chambre->etudiante_2 }}
                                </span>
                            @else
                                <span class="text-muted fst-italic small">Place libre</span>
                            @endif
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>

                    {{-- Statut --}}
                    <td>
                        @if($chambre->statut === 'libre')
                            <span class="badge bg-success">Disponible</span>
                        @elseif($chambre->statut === 'partielle')
                            <span class="badge bg-warning text-dark">1 place libre</span>
                        @else
                            <span class="badge bg-danger">Occupée</span>
                        @endif
                    </td>

                    <td>
    @if($chambre->publiee)
        <form method="POST" action="{{ route('hebergement.chambres.depublier', $chambre) }}">
            @csrf @method('PATCH')
            <button class="badge bg-primary border-0" title="Cliquer pour dépublier">Oui</button>
        </form>
    @else
        <span class="badge bg-secondary">Non</span>
    @endif
</td>

                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('hebergement.chambres.edit', $chambre) }}"
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('hebergement.chambres.destroy', $chambre) }}"
                                  onsubmit="return confirm('Supprimer cette chambre ?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center text-muted py-4">Aucune chambre enregistrée.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $chambres->links() }}</div>

@endsection