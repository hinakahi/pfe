@extends('layouts.app')

@section('page-title', 'Mes demandes de maintenance')

@section('content')
<div class="container-fluid">

    {{-- Formulaire nouvelle demande --}}
    <div class="card shadow-sm border-0 mb-5">
        <div class="card-header bg-white border-bottom-0 pt-4 pb-3">
            <h5 class="mb-0 text-dark fw-bold">
                <i class="bi bi-plus-circle-fill me-2" style="color:#0066cc;"></i>Nouvelle demande
            </h5>
        </div>
        <div class="card-body pt-4">
            <form method="POST" action="{{ route('etudiante.maintenance.store') }}">
                @csrf

                <div class="row g-4">
                    {{-- Chambre --}}
                     <div class="col-md-6">
    <label class="form-label fw-semibold">Chambre <span class="text-danger">*</span></label>
    <select name="chambre_id" class="form-select @error('chambre_id') is-invalid @enderror" required>
        @foreach($chambres as $chambre)
            <option value="{{ $chambre->id }}" 
                {{ auth()->user()->chambre_id == $chambre->id ? 'selected' : '' }}>
                Chambre {{ $chambre->numero }}
            </option>
        @endforeach
    </select>
    @error('chambre_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

                    {{-- Type de panne --}}
                    <div class="col-md-6">
                        <label class="form-label fw-600 text-dark mb-2">
                            Type de panne <span class="text-danger">*</span>
                        </label>
                        <select name="type" class="form-select form-select-lg @error('type') is-invalid @enderror" required>
                            <option value="">Sélectionnez un type</option>
                            <option value="electricite" {{ old('type') === 'electricite' ? 'selected' : '' }}>⚡ Électricité</option>
                            <option value="plomberie"   {{ old('type') === 'plomberie'   ? 'selected' : '' }}>💧 Plomberie</option>
                            <option value="menuiserie"  {{ old('type') === 'menuiserie'  ? 'selected' : '' }}>🚪 Menuiserie</option>
                            <option value="climatisation" {{ old('type') === 'climatisation' ? 'selected' : '' }}>❄️ Climatisation</option>
                            <option value="autre"       {{ old('type') === 'autre'       ? 'selected' : '' }}>🔧 Autre</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div class="col-md-8">
                        <label class="form-label fw-600 text-dark mb-2">
                            Description <span class="text-danger">*</span>
                        </label>
                        <textarea name="description" rows="4"
                                  class="form-control form-control-lg @error('description') is-invalid @enderror"
                                  placeholder="Décrivez le problème en détail..."
                                  required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Urgence --}}
                    <div class="col-md-4">
                        <label class="form-label fw-600 text-dark mb-3">
                            Urgence <span class="text-danger">*</span>
                        </label>
                        <div class="d-flex flex-column gap-3">
                            <div class="form-check d-flex align-items-center">
                                <input class="form-check-input" type="radio" name="urgence"
                                       id="normale" value="normale"
                                       {{ old('urgence', 'normale') === 'normale' ? 'checked' : '' }}>
                                <label class="form-check-label ms-2 fw-500" for="normale">
                                    <span class="badge bg-secondary px-3 py-2">Normale</span>
                                </label>
                            </div>
                            <div class="form-check d-flex align-items-center">
                                <input class="form-check-input" type="radio" name="urgence"
                                       id="urgente" value="urgente"
                                       {{ old('urgence') === 'urgente' ? 'checked' : '' }}>
                                <label class="form-check-label ms-2 fw-500" for="urgente">
                                    <span class="badge bg-danger px-3 py-2">Urgente</span>
                                </label>
                            </div>
                        </div>
                        @error('urgence')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Bouton submit --}}
                    <div class="col-12 pt-3">
                        <button type="submit" class="btn btn-primary btn-lg px-5" style="border-radius:8px;">
                            <i class="bi bi-send me-2"></i>Envoyer la demande
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Liste des demandes en CARTES --}}
    <div>
        <div class="mb-4">
            <h5 class="text-dark fw-bold mb-1">
                <i class="bi bi-list-check me-2" style="color:#0066cc;"></i>Mes demandes
            </h5>
            <small class="text-muted">{{ $demandes->count() }} demande(s) au total</small>
        </div>

        @forelse($demandes as $d)
            <div class="card shadow-sm border-0 mb-4" style="border-left: 4px solid {{ $d->urgence === 'urgente' ? '#dc3545' : '#6c757d' }};">
                <div class="card-body p-4">
                    {{-- Header de la carte --}}
                    <div class="row align-items-start mb-4">
                        <div class="col">
                            {{-- Type + Description --}}
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <span class="badge" style="background-color: {{ $typeColor = match($d->type) {
                                    'electricite' => '#FFB81C',
                                    'plomberie' => '#0066cc',
                                    'menuiserie' => '#8B4513',
                                    'climatisation' => '#17A2B8',
                                    default => '#6c757d'
                                } }}; color:white; padding:6px 12px; border-radius:6px; font-size:0.9rem;">
                                    {{ match($d->type) {
                                        'electricite' => '⚡ Électricité',
                                        'plomberie' => '💧 Plomberie',
                                        'menuiserie' => '🚪 Menuiserie',
                                        'climatisation' => '❄️ Climatisation',
                                        default => '🔧 ' . ucfirst($d->type)
                                    } }}
                                </span>
                                
                                {{-- Urgence badge --}}
                                @if($d->urgence === 'urgente')
                                    <span class="badge bg-danger px-3 py-2">
                                        <i class="bi bi-exclamation-triangle-fill me-1"></i>Urgente
                                    </span>
                                @else
                                    <span class="badge bg-secondary px-3 py-2">Normale</span>
                                @endif
                            </div>

                            {{-- Description --}}
                            <p class="text-dark fw-500 mb-0" style="font-size:1.1rem;">
                                {{ $d->description }}
                            </p>
                        </div>

                        {{-- ID petit --}}
                        <div class="col-auto">
                            <small class="text-muted">#{{ $d->id }}</small>
                        </div>
                    </div>

                    {{-- Détails --}}
                    <div class="row g-4 mb-4" style="border-top:1px solid #e9ecef; padding-top:1rem;">
                        <div class="col-auto">
                            <small class="text-muted d-block">Chambre</small>
                            <p class="mb-0 fw-600 text-dark">🏠 Chambre {{ $d->chambre->numero ?? '—' }}</p>
                        </div>
                        <div class="col-auto">
                            <small class="text-muted d-block">Signalée le</small>
                            <p class="mb-0 fw-600 text-dark">{{ $d->date_signalement?->format('d/m/Y') ?? '—' }}</p>
                        </div>
                        <div class="col-auto">
                            <small class="text-muted d-block">Statut</small>
                            <p class="mb-0">
                                @if($d->statut === 'en_attente')
                                    <span class="badge bg-warning text-dark px-3 py-2">
                                        <i class="bi bi-hourglass-split me-1"></i>En attente
                                    </span>
                                @elseif($d->statut === 'en_cours')
                                    <span class="badge bg-primary px-3 py-2">
                                        <i class="bi bi-gear me-1"></i>En cours
                                    </span>
                                @else
                                    <span class="badge bg-success px-3 py-2">
                                        <i class="bi bi-check-circle-fill me-1"></i>Terminée
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div style="border-top:1px solid #e9ecef; padding-top:1rem;">
                        @if($d->statut === 'en_attente')
                            <form method="POST" action="{{ route('etudiante.maintenance.destroy', $d->id) }}"
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette demande ?');"
                                  style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm px-4" style="border-radius:6px;">
                                    <i class="bi bi-x-circle me-1"></i>Annuler
                                </button>
                            </form>
                        @else
                            <span class="text-muted small">Aucune action possible</span>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="card shadow-sm border-0 text-center py-5" style="background:#f8f9fa;">
                <div class="card-body">
                    <i class="bi bi-inbox fs-1 opacity-25"></i>
                    <p class="mt-3 text-muted mb-0">Aucune demande de maintenance pour le moment.</p>
                </div>
            </div>
        @endforelse
    </div>

</div>

<style>
    .form-check-input {
        width: 1.5rem;
        height: 1.5rem;
        border: 2px solid #ddd;
        border-radius: 4px;
    }
    .form-check-input:checked {
        background-color: #0066cc;
        border-color: #0066cc;
    }
    .form-select-lg, .form-control-lg {
        padding: 0.75rem 1rem;
        font-size: 1rem;
        border-radius: 8px;
        border: 1px solid #ddd;
    }
    .form-select-lg:focus, .form-control-lg:focus {
        border-color: #0066cc;
        box-shadow: 0 0 0 0.2rem rgba(0, 102, 204, 0.15);
    }
    .badge {
        font-weight: 500;
        letter-spacing: 0.3px;
    }
    .fw-600 {
        font-weight: 600;
    }
    .fw-500 {
        font-weight: 500;
    }
</style>

@endsection