@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0 fw-bold">Mes demandes de maintenance</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCreer">
            <i class="bi bi-plus-lg me-1"></i> Nouvelle demande
        </button>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
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
    @forelse($demandes as $demande)
    <div class="card mb-3 border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        @if($demande->urgence === 'urgente')
                            <span class="badge bg-danger">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i>Urgente
                            </span>
                        @else
                            <span class="badge bg-secondary">Normale</span>
                        @endif

                        @php
                            $statutClass = match($demande->statut) {
                                'en_attente' => 'warning text-dark',
                                'en_cours'   => 'info text-dark',
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
                        <span class="badge bg-{{ $statutClass }}">{{ $statutLabel }}</span>
                        <span class="text-muted small">{{ ucfirst($demande->type) }}</span>
                    </div>

                    <p class="mb-1 text-truncate" style="max-width: 600px;">
                        {{ $demande->description }}
                    </p>

                    <div class="text-muted small">
                        <i class="bi bi-door-open me-1"></i>Chambre {{ $demande->chambre->numero ?? '—' }}
                        &nbsp;·&nbsp;
                        <i class="bi bi-building me-1"></i>Bloc {{ $demande->chambre->bloc ?? '—' }}
                        &nbsp;·&nbsp;
                        <i class="bi bi-layers me-1"></i>Étage {{ $demande->chambre->etage ?? '—' }}
                        &nbsp;·&nbsp;
                        <i class="bi bi-clock me-1"></i>{{ $demande->created_at->format('d/m/Y') }}
                        @if($demande->technicien)
                            &nbsp;·&nbsp;
                            <i class="bi bi-person-gear me-1"></i>{{ $demande->technicien->name }}
                        @endif
                    </div>
                </div>

                <div class="d-flex gap-2 ms-3 flex-shrink-0">
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
        <div class="text-center py-5 text-muted">
            <i class="bi bi-tools fs-1 d-block mb-3"></i>
            <p class="mb-0">Aucune demande trouvée.</p>
            @if(request()->hasAny(['statut','urgence','periode']))
                <a href="{{ route('etudiante.maintenance.index') }}" class="btn btn-sm btn-outline-secondary mt-3">
                    Effacer les filtres
                </a>
            @endif
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
            <form action="{{ route('etudiante.maintenance.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">

                        {{-- Chambre automatique --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Chambre</label>
                            @if($chambre)
                                <input type="hidden" name="chambre_id" value="{{ $chambre->id }}">
                                <input type="text" class="form-control bg-light"
                                       value="Chambre {{ $chambre->numero }}" readonly>
                            @else
                                <div class="alert alert-warning py-2 mb-0 small">
                                    Aucune chambre assignée à votre compte.
                                </div>
                            @endif
                            @error('chambre_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        {{-- Bloc (automatique depuis la chambre) --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Bloc</label>
                            <input type="text" class="form-control bg-light"
                                   value="{{ $chambre->bloc ?? '—' }}" readonly>
                        </div>

                        {{-- Étage (automatique depuis la chambre) --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Étage</label>
                            <input type="text" class="form-control bg-light"
                                   value="{{ $chambre->etage ?? '—' }}" readonly>
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

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary" @unless($chambre) disabled @endunless>
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

@endsection