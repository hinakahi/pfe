@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width: 750px;">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('etudiante.maintenance.index') }}">Maintenance</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('etudiante.maintenance.show', $maintenance) }}">Demande #{{ $maintenance->id }}</a>
            </li>
            <li class="breadcrumb-item active">Modifier</li>
        </ol>
    </nav>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="mb-0 fw-bold">Modifier la demande</h5>
        </div>

        <form action="{{ route('etudiante.maintenance.update', $maintenance) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="card-body p-4">
                <div class="row g-3">

                    <div class="col-12 mb-2">
                        <label class="form-label fw-semibold">Où se situe le problème ? <span class="text-danger">*</span></label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type_lieu" id="lieuChambre" value="chambre"
                                       {{ $maintenance->chambre_id ? 'checked' : '' }} onclick="toggleLieu()">
                                <label class="form-check-label" for="lieuChambre">Ma chambre</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type_lieu" id="lieuCommun" value="commun"
                                       {{ !$maintenance->chambre_id ? 'checked' : '' }} onclick="toggleLieu()">
                                <label class="form-check-label" for="lieuCommun">Espace commun</label>
                            </div>
                        </div>
                    </div>

                    <div id="blocChambre" class="row g-3 {{ !$maintenance->chambre_id ? 'd-none' : '' }}">
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

                    <div id="blocCommun" class="row g-3 {{ $maintenance->chambre_id ? 'd-none' : '' }}">
                        @if(!$maintenance->chambre_id)
                        <div class="col-12">
                            <div class="alert alert-secondary py-2 small mb-2">
                                Lieu actuel : <strong>{{ $maintenance->lieu_commun }}</strong> 
                            </div>
                        </div>
                        @endif
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
                            <option value="electricite" {{ old('type', $maintenance->type) === 'electricite' ? 'selected' : '' }}>Électricité</option>
                            <option value="plomberie"   {{ old('type', $maintenance->type) === 'plomberie'   ? 'selected' : '' }}>Plomberie</option>
                            <option value="menuiserie"  {{ old('type', $maintenance->type) === 'menuiserie'  ? 'selected' : '' }}>Menuiserie</option>
                            <option value="autre"       {{ old('type', $maintenance->type) === 'autre'       ? 'selected' : '' }}>Autre</option>
                        </select>
                        @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Urgence <span class="text-danger">*</span></label>
                        <select name="urgence" class="form-select @error('urgence') is-invalid @enderror" required>
                            <option value="normale" {{ old('urgence', $maintenance->urgence) === 'normale' ? 'selected' : '' }}>Normale</option>
                            <option value="urgente" {{ old('urgence', $maintenance->urgence) === 'urgente' ? 'selected' : '' }}>Urgente</option>
                        </select>
                        @error('urgence') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
                        <textarea name="description" rows="5"
                                  class="form-control @error('description') is-invalid @enderror"
                                  placeholder="Décrivez le problème..." required>{{ old('description', $maintenance->description) }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Photo (optionnel)</label>

                        @if($maintenance->photo)
                        <div class="mb-2">
                            <a href="{{ asset('storage/' . $maintenance->photo) }}" target="_blank">
                                <img src="{{ asset('storage/' . $maintenance->photo) }}" alt="Photo actuelle"
                                     style="max-height:120px;border-radius:.375rem;" class="border">
                            </a>
                            <div class="form-text">Photo actuelle. Choisir un nouveau fichier la remplacera.</div>
                        </div>
                        @endif

                        <input type="file" name="photo" accept="image/*"
                               class="form-control @error('photo') is-invalid @enderror">
                        @error('photo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                </div>

                <div class="alert alert-warning mt-4 py-2 small mb-0">
                    <i class="bi bi-info-circle me-1"></i>
                    Seules les demandes <strong>en attente</strong> peuvent être modifiées.
                </div>
            </div>

            <div class="card-footer bg-white border-top py-3 d-flex justify-content-between">
                <a href="{{ route('etudiante.maintenance.show', $maintenance) }}"
                   class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Retour
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i>Enregistrer les modifications
                </button>
            </div>

        </form>
    </div>

</div>

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