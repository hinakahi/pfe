@extends('layouts.app')

@section('page-title', 'Modifier le stock')

@section('content')
<div class="container-fluid">

    <a href="{{ route('technicien.stock.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill mb-4">
        <i class="bi bi-arrow-left me-1"></i>Retour au stock
    </a>

    <div class="card" style="max-width:600px;">
        <div class="card-header">
            <h6 class="mb-0"><i class="bi bi-pencil me-2 text-primary"></i>Modifier — {{ $stock->designation }}</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('technicien.stock.update', $stock->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-semibold">Désignation <span class="text-danger">*</span></label>
                    <input type="text" name="designation"
                           class="form-control @error('designation') is-invalid @enderror"
                           value="{{ old('designation', $stock->designation) }}" required>
                    @error('designation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="form-label fw-semibold">Quantité <span class="text-danger">*</span></label>
                        <input type="number" name="quantite"
                               class="form-control @error('quantite') is-invalid @enderror"
                               value="{{ old('quantite', $stock->quantite) }}" min="0" required>
                        @error('quantite')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold">Unité <span class="text-danger">*</span></label>
                        <select name="unite" class="form-select @error('unite') is-invalid @enderror" required>
                            <option value="pcs"    {{ old('unite', $stock->unite) === 'pcs'    ? 'selected' : '' }}>Pièces (pcs)</option>
                            <option value="m"      {{ old('unite', $stock->unite) === 'm'      ? 'selected' : '' }}>Mètres (m)</option>
                            <option value="kg"     {{ old('unite', $stock->unite) === 'kg'     ? 'selected' : '' }}>Kilogrammes (kg)</option>
                            <option value="litre"  {{ old('unite', $stock->unite) === 'litre'  ? 'selected' : '' }}>Litres</option>
                            <option value="boite"  {{ old('unite', $stock->unite) === 'boite'  ? 'selected' : '' }}>Boîtes</option>
                            <option value="flacon" {{ old('unite', $stock->unite) === 'flacon' ? 'selected' : '' }}>Flacons</option>
                        </select>
                        @error('unite')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Seuil minimum <span class="text-danger">*</span></label>
                    <input type="number" name="seuil_minimum"
                           class="form-control @error('seuil_minimum') is-invalid @enderror"
                           value="{{ old('seuil_minimum', $stock->seuil_minimum) }}" min="0" required>
                    <div class="form-text">Alerte quand le stock descend sous ce seuil.</div>
                    @error('seuil_minimum')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Catégorie <span class="text-danger">*</span></label>
                    <select name="categorie" class="form-select @error('categorie') is-invalid @enderror" required>
                        <option value="electricite"   {{ old('categorie', $stock->categorie) === 'electricite'   ? 'selected' : '' }}>Électricité</option>
                        <option value="plomberie"     {{ old('categorie', $stock->categorie) === 'plomberie'     ? 'selected' : '' }}>Plomberie</option>
                        <option value="menuiserie"    {{ old('categorie', $stock->categorie) === 'menuiserie'    ? 'selected' : '' }}>Menuiserie</option>
                        <option value="autre"         {{ old('categorie', $stock->categorie) === 'autre'         ? 'selected' : '' }}>Autre</option>
                    </select>
                    @error('categorie')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Description</label>
                    <textarea name="description" class="form-control" rows="2">{{ old('description', $stock->description) }}</textarea>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Photo</label>

                    {{-- Afficher la photo actuelle si elle existe --}}
                    @if ($stock->photo)
                        <div class="mb-2">
                            <img src="{{ Storage::url($stock->photo) }}"
                                 alt="photo actuelle"
                                 class="img-thumbnail"
                                  style="max-height: 250px; width:100%; object-fit:cover;">
                            <div class="form-text">Photo actuelle — choisir une nouvelle photo pour la remplacer.</div>
                        </div>
                    @endif

                    <input type="file" name="photo"
                           class="form-control @error('photo') is-invalid @enderror"
                           accept="image/*">
                    <div class="form-text">Formats acceptés : JPG, PNG, WEBP — max 2 Mo.</div>
                    @error('photo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-floppy me-1"></i>Enregistrer les modifications
                    </button>
                    <a href="{{ route('technicien.stock.index') }}" class="btn btn-outline-secondary">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection