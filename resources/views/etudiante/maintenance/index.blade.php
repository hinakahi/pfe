@extends('layouts.app')
@section('page-title', 'Mes demandes de maintenance')
@section('content')
<div class="container py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCreer">
            <i class="bi bi-plus-lg me-1"></i> Nouvelle demande
        </button>
    </div>

    {{-- Alerts --}}

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Filtres --}}
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body">
            <form method="GET" action="{{ route('etudiante.maintenance.index') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-semibold small text-muted">Statut</label>
                    <select name="statut" class="form-select form-select-sm">
                        <option value="">Tous les statuts</option>
                        <option value="en_attente" {{ request('statut') === 'en_attente' ? 'selected' : '' }}>En attente</option>
                        <option value="en_cours"   {{ request('statut') === 'en_cours'   ? 'selected' : '' }}>En cours</option>
                        <option value="terminee"   {{ request('statut') === 'terminee'   ? 'selected' : '' }}>Terminée</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold small text-muted">Urgence</label>
                    <select name="urgence" class="form-select form-select-sm">
                        <option value="">Toutes</option>
                        <option value="normale" {{ request('urgence') === 'normale' ? 'selected' : '' }}>Normale</option>
                        <option value="urgente" {{ request('urgence') === 'urgente' ? 'selected' : '' }}>Urgente</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold small text-muted">Période</label>
                    <select name="periode" class="form-select form-select-sm">
                        <option value="">Toutes les dates</option>
                        <option value="7"  {{ request('periode') === '7'  ? 'selected' : '' }}>7 derniers jours</option>
                        <option value="30" {{ request('periode') === '30' ? 'selected' : '' }}>30 derniers jours</option>
                        <option value="90" {{ request('periode') === '90' ? 'selected' : '' }}>90 derniers jours</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-outline-primary btn-sm flex-fill">
                        <i class="bi bi-funnel me-1"></i>Filtrer
                    </button>
                    <a href="{{ route('etudiante.maintenance.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-x-lg"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Liste --}}
    @php
        $typeIcons = [
            'electricite' => 'bi-lightning-charge',
            'plomberie'   => 'bi-droplet',
            'menuiserie'  => 'bi-hammer',
            'autre'       => 'bi-tools',
        ];
    @endphp

    @forelse($demandes as $demande)
    @php
        $borderColor = match($demande->statut) {
            'en_attente' => '#ffc107',
            'en_cours'   => '#0d6efd',
            'terminee'   => '#198754',
            default      => '#dee2e6',
        };
        $statutClass = match($demande->statut) {
            'en_attente' => 'warning text-dark',
            'en_cours'   => 'primary',
            'terminee'   => 'success',
            default      => 'secondary',
        };
        $statutLabel = match($demande->statut) {
            'en_attente' => 'En attente',
            'en_cours'   => 'En cours',
            'terminee'   => 'Terminée',
            default      => $demande->statut,
        };
    @endphp
    <div class="card mb-3 shadow-sm" style="border-left: 5px solid {{ $borderColor }};">
        <div class="card-body py-3">
            <div class="d-flex flex-wrap align-items-start justify-content-between gap-3">

                <div style="flex:1; min-width:240px;">

                    {{-- Badges --}}
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                        <span class="badge bg-{{ $statutClass }}">{{ $statutLabel }}</span>

                        @if($demande->urgence === 'urgente')
                            <span class="badge bg-danger">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i>Urgente
                            </span>
                        @else
                            <span class="badge bg-light text-dark border">Normale</span>
                        @endif

                        <span class="badge bg-light text-dark border">
                            <i class="bi {{ $typeIcons[$demande->type] ?? 'bi-tools' }} me-1"></i>
                            {{ ucfirst($demande->type) }}
                        </span>
                    </div>

                    {{-- Description --}}
                    <p class="mb-2 text-truncate" style="max-width: 600px;">
                        {{ $demande->description }}
                    </p>

                    {{-- Localisation + date + technicien --}}
                    <div class="d-flex flex-wrap gap-3 text-muted" style="font-size:.85rem;">
                        @if($demande->chambre)
                            <span><i class="bi bi-door-closed me-1"></i>Chambre {{ $demande->chambre->numero }}</span>
                            <span><i class="bi bi-building me-1"></i>Bloc {{ $demande->chambre->bloc }}</span>
                            <span><i class="bi bi-layers me-1"></i>Étage {{ $demande->chambre->etage }}</span>
                        @elseif($demande->lieu_commun)
                            <span><i class="bi bi-geo-alt me-1"></i>{{ $demande->lieu_commun }}</span>
                        @endif
                        <span><i class="bi bi-calendar me-1"></i>{{ $demande->created_at->format('d/m/Y') }}</span>
                        @if($demande->technicien)
                            <span class="text-primary"><i class="bi bi-person-gear me-1"></i>{{ $demande->technicien->name }}</span>
                        @endif
                    </div>

                </div>

                {{-- Actions --}}
                <div class="d-flex gap-2 flex-shrink-0">
                    <a href="{{ route('etudiante.maintenance.show', $demande) }}"
                       class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-eye me-1"></i>Détails
                    </a>

                    @if($demande->statut === 'en_attente')
                        <a href="{{ route('etudiante.maintenance.edit', $demande) }}"
                           class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-pencil me-1"></i>Modifier
                        </a>

                        <form action="{{ route('etudiante.maintenance.destroy', $demande) }}"
                              method="POST"
                              onsubmit="return confirm('Annuler cette demande ?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="bi bi-x-circle me-1"></i>Annuler
                            </button>
                        </form>
                    @endif
                </div>

            </div>
        </div>
    </div>
    @empty
        <div class="card">
            <div class="card-body text-center text-muted py-5">
                <i class="bi bi-tools fs-1 opacity-25 d-block mb-3"></i>
                <p class="mb-0">Aucune demande trouvée.</p>
                @if(request()->hasAny(['statut','urgence','periode']))
                    <a href="{{ route('etudiante.maintenance.index') }}" class="btn btn-sm btn-outline-secondary mt-3">
                        Effacer les filtres
                    </a>
                @endif
            </div>
        </div>
    @endforelse

    {{-- Pagination --}}
    @if($demandes->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $demandes->appends(request()->query())->links() }}
        </div>
    @endif

</div>

{{-- Modal Créer --}}
<div class="modal fade" id="modalCreer" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Nouvelle demande de maintenance</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('etudiante.maintenance.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">

                         <div class="col-12 mb-2">
    <label class="form-label fw-semibold">Où se situe le problème ? <span class="text-danger">*</span></label>
    <div class="d-flex gap-3">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="type_lieu" id="lieuChambre" value="chambre" checked onclick="toggleLieu()">
            <label class="form-check-label" for="lieuChambre">Ma chambre</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="type_lieu" id="lieuCommun" value="commun" onclick="toggleLieu()">
            <label class="form-check-label" for="lieuCommun">Espace commun </label>
        </div>
    </div>
</div>

<div id="blocChambre" class="row g-3">
    <div class="col-md-4">
        <label class="form-label fw-semibold">Chambre</label>
        @if($chambre)
            <input type="hidden" name="chambre_id" value="{{ $chambre->id }}">
            <input type="text" class="form-control bg-light" value="Chambre {{ $chambre->numero }}" readonly>
        @else
            <div class="alert alert-warning py-2 mb-0 small">Aucune chambre assignée.</div>
        @endif
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Bloc</label>
        <input type="text" class="form-control bg-light" value="{{ $chambre->bloc ?? '—' }}" readonly>
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Étage</label>
        <input type="text" class="form-control bg-light" value="{{ $chambre->etage ?? '—' }}" readonly>
    </div>
</div>

<div id="blocCommun" class="row g-3 d-none">
    <div class="col-md-6">
        <label class="form-label fw-semibold">Lieu <span class="text-danger">*</span></label>
        <select name="lieu_type" id="selectLieuType" class="form-select">
            <option value="">Choisir</option>
            <option value="Couloir">Couloir</option>
            <option value="Sanitaires">Sanitaires</option>
            <option value="Douches">Douches</option>
            <option value="Cuisine commune">Cuisine commune</option>
            <option value="Salle commune">Salle commune</option>
            <option value="Autre">Autre</option>
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label fw-semibold">Bloc</label>
        <input type="text" name="lieu_bloc" id="inputLieuBloc" class="form-control" placeholder="Ex : B">
    </div>
    <div class="col-md-3">
        <label class="form-label fw-semibold">Étage</label>
        <input type="text" name="lieu_etage" id="inputLieuEtage" class="form-control" placeholder="Ex : 2">
    </div>
    <div class="col-12" id="blocAutrePrecision" style="display:none;">
        <label class="form-label fw-semibold">Précisez</label>
        <input type="text" name="lieu_autre" id="inputLieuAutre" class="form-control" placeholder="Précisez le lieu...">
    </div>
</div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Type <span class="text-danger">*</span></label>
                            <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                                <option value="">Choisir</option>
                                <option value="electricite" {{ old('type') === 'electricite' ? 'selected' : '' }}>Électricité</option>
                                <option value="plomberie"   {{ old('type') === 'plomberie'   ? 'selected' : '' }}>Plomberie</option>
                                <option value="menuiserie"  {{ old('type') === 'menuiserie'  ? 'selected' : '' }}>Menuiserie</option>
                                <option value="autre"       {{ old('type') === 'autre'       ? 'selected' : '' }}>Autre</option>
                            </select>
                            @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Urgence <span class="text-danger">*</span></label>
                            <select name="urgence" class="form-select @error('urgence') is-invalid @enderror" required>
                                <option value="normale" {{ old('urgence') === 'normale' ? 'selected' : '' }}>Normale</option>
                                <option value="urgente" {{ old('urgence') === 'urgente' ? 'selected' : '' }}>Urgente</option>
                            </select>
                            @error('urgence') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
                            <textarea name="description" rows="4"
                                      class="form-control @error('description') is-invalid @enderror"
                                      placeholder="Décrivez le problème en détail..." required>{{ old('description') }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Photo (optionnel)</label>
                            <input type="file" name="photo" accept="image/*"
                                   class="form-control @error('photo') is-invalid @enderror">
                            @error('photo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-send me-1"></i>Envoyer la demande
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@if($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function () {
        new bootstrap.Modal(document.getElementById('modalCreer')).show();
    });
</script>
@endif
<script>
function toggleLieu() {
    const estCommun = document.getElementById('lieuCommun').checked;
    document.getElementById('blocChambre').classList.toggle('d-none', estCommun);
    document.getElementById('blocCommun').classList.toggle('d-none', !estCommun);
    document.getElementById('selectLieuType').required = estCommun;
}

document.getElementById('selectLieuType').addEventListener('change', function () {
    const estAutre = this.value === 'Autre';
    document.getElementById('blocAutrePrecision').style.display = estAutre ? 'block' : 'none';
    document.getElementById('inputLieuAutre').required = estAutre;
});
</script>

@endsection