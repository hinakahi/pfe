@extends('layouts.app')
@section('page-title', 'Modifier la chambre')

@section('content')

<div class="mb-4">
    <h4 class="fw-bold mb-1">Modifier la chambre {{ $chambre->numero }}</h4>
    <p class="text-muted mb-0">Mettre à jour les informations et l'affectation des étudiantes.</p>
</div>

<div class="card" style="max-width:600px">
    <div class="card-body">
        <form method="POST" action="{{ route('hebergement.chambres.update', $chambre) }}">
            @csrf @method('PUT')

            <div class="mb-3">
                <label class="form-label">Numéro de chambre</label>
                <input type="text" class="form-control" value="{{ $chambre->numero }}" disabled>
            </div>

            <div class="mb-3">
                <label class="form-label">Type</label>
                <input type="text" class="form-control" value="{{ ucfirst($chambre->type) }}" disabled>
            </div>

            <div class="row mb-3">
                <div class="col">
                    <label class="form-label">Bloc / Pavillon</label>
                    <input type="text" class="form-control" value="{{ $chambre->bloc }}" disabled>
                </div>
                <div class="col">
                    <label class="form-label">Étage</label>
                    <input type="text" class="form-control" value="{{ $chambre->etage }}" disabled>
                </div>
            </div>

            {{-- Étudiante 1 --}}
<div class="mb-3">
    <label class="form-label fw-semibold">
        <i class="bi bi-person me-1"></i> Étudiante 1
    </label>
    <input type="text" name="etudiante_1" class="form-control"
           value="{{ old('etudiante_1', $chambre->etudiante_1) }}"
           placeholder="Nom complet (laisser vide = place libre)">
</div>

{{-- Étudiante 2 (seulement pour les doubles) --}}
@if($chambre->type === 'double')
<div class="mb-3">
    <label class="form-label fw-semibold">
        <i class="bi bi-person me-1"></i> Étudiante 2
    </label>
    <input type="text" name="etudiante_2" class="form-control"
           value="{{ old('etudiante_2', $chambre->etudiante_2) }}"
           placeholder="Nom complet (laisser vide = place libre)">
</div>
@endif
            {{-- Statut calculé affiché en lecture seule --}}
            <div class="mb-4">
                <label class="form-label">Statut actuel</label>
                <div>
                    @if($chambre->statut === 'libre')
                        <span class="badge bg-success fs-6">Disponible</span>
                    @elseif($chambre->statut === 'partielle')
                        <span class="badge bg-warning text-dark fs-6">1 place libre</span>
                    @else
                        <span class="badge bg-danger fs-6">Occupée</span>
                    @endif
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-lg me-1"></i> Enregistrer
                </button>
                <a href="{{ route('hebergement.chambres.index') }}" class="btn btn-outline-secondary">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>

@endsection