@extends('layouts.app')
@section('page-title', 'Ajouter une chambre')
@section('content')

<div class="mb-4">
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('hebergement.chambres.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h4 class="fw-bold mb-0">Ajouter une chambre</h4>
    </div>
</div>

<div class="card border-0 shadow-sm" style="max-width:600px;">
    <div class="card-body">
        <form action="{{ route('hebergement.chambres.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-semibold">Numéro de chambre</label>
                <input type="text" name="numero" class="form-control @error('numero') is-invalid @enderror"
                       value="{{ old('numero') }}" required>
                @error('numero') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Type</label>
                <select name="type" id="typeSelect" class="form-select @error('type') is-invalid @enderror" required>
                    <option value="">-- Choisir --</option>
                    <option value="individuelle" {{ old('type') == 'individuelle' ? 'selected' : '' }}>Individuelle</option>
                    <option value="double" {{ old('type') == 'double' ? 'selected' : '' }}>Double</option>
                </select>
                @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Bloc</label>
                <input type="text" name="bloc" class="form-control @error('bloc') is-invalid @enderror"
                       value="{{ old('bloc') }}" required>
                @error('bloc') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Étage</label>
                <input type="number" name="etage" class="form-control @error('etage') is-invalid @enderror"
                       value="{{ old('etage', 0) }}" min="0" required>
                @error('etage') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Capacité</label>
                <input type="number" name="capacite" class="form-control @error('capacite') is-invalid @enderror"
                       value="{{ old('capacite', 1) }}" min="1" max="2" required>
                @error('capacite') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Étudiante 1 (matricule)</label>
                <input type="text" name="etudiante_1" class="form-control"
                       value="{{ old('etudiante_1') }}" placeholder="Laisser vide si libre">
            </div>

            <div class="mb-3" id="etudiante2Field" style="display:none;">
                <label class="form-label fw-semibold">Étudiante 2 (matricule)</label>
                <input type="text" name="etudiante_2" class="form-control"
                       value="{{ old('etudiante_2') }}" placeholder="Laisser vide si libre">
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i> Enregistrer
                </button>
                <a href="{{ route('hebergement.chambres.index') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('typeSelect').addEventListener('change', function() {
        document.getElementById('etudiante2Field').style.display =
            this.value === 'double' ? 'block' : 'none';
    });
</script>

@endsection