@extends('layouts.app')

@section('page-title', 'Ajouter un matériel')

@section('content')
<div class="container-fluid">

    <a href="{{ route('technicien.stock.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill mb-4">
        <i class="bi bi-arrow-left me-1"></i>Retour au stock
    </a>

    <div class="card">
        <div class="card-header">
            <h6 class="mb-0"><i class="bi bi-plus-circle me-2 text-primary"></i>Nouveau matériel</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('technicien.stock.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Désignation <span class="text-danger">*</span></label>
                    <input type="text" name="designation"
                           class="form-control @error('designation') is-invalid @enderror"
                           value="{{ old('designation') }}"
                           placeholder="ex: Câble électrique 2.5mm" required>
                    @error('designation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="form-label fw-semibold">Quantité <span class="text-danger">*</span></label>
                        <input type="number" name="quantite"
                               class="form-control @error('quantite') is-invalid @enderror"
                               value="{{ old('quantite', 0) }}" min="0" required>
                        @error('quantite')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold">Unité <span class="text-danger">*</span></label>
                        <select name="unite" class="form-select @error('unite') is-invalid @enderror" required>
                            <option value="pcs"    {{ old('unite') === 'pcs'    ? 'selected' : '' }}>Pièces (pcs)</option>
                            <option value="m"      {{ old('unite') === 'm'      ? 'selected' : '' }}>Mètres (m)</option>
                            <option value="kg"     {{ old('unite') === 'kg'     ? 'selected' : '' }}>Kilogrammes (kg)</option>
                            <option value="litre"  {{ old('unite') === 'litre'  ? 'selected' : '' }}>Litres</option>
                            <option value="boite"  {{ old('unite') === 'boite'  ? 'selected' : '' }}>Boîtes</option>
                            <option value="flacon" {{ old('unite') === 'flacon' ? 'selected' : '' }}>Flacons</option>
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
                           value="{{ old('seuil_minimum', 5) }}" min="0" required>
                    <div class="form-text">Alerte quand le stock descend sous ce seuil.</div>
                    @error('seuil_minimum')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Catégorie <span class="text-danger">*</span></label>
                    <select name="categorie" class="form-select @error('categorie') is-invalid @enderror" required>
                        <option value="electricite"   {{ old('categorie') === 'electricite'   ? 'selected' : '' }}>Électricité</option>
                        <option value="plomberie"     {{ old('categorie') === 'plomberie'     ? 'selected' : '' }}>Plomberie</option>
                        <option value="menuiserie"    {{ old('categorie') === 'menuiserie'    ? 'selected' : '' }}>Menuiserie</option>
                        <option value="autre"         {{ old('categorie') === 'autre'         ? 'selected' : '' }}>Autre</option>
                    </select>
                    @error('categorie')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Description</label>
                    <textarea name="description" class="form-control" rows="2"
                              placeholder="Description optionnelle…">{{ old('description') }}</textarea>
                </div>
                <div class="mb-4">
    <label class="form-label fw-semibold">Photo</label>

    <div class="mb-2" id="previewBox" style="display:none;">
        <img id="previewImg" src="" alt="preview"
             style="width:100%; height:200px; object-fit:cover; border-radius:8px;">
    </div>

    <input type="file" name="photo" id="photoInput"
           class="form-control @error('photo') is-invalid @enderror"
           accept="image/*"
           onchange="previewPhoto(this)">
    <div class="form-text">Formats acceptés : JPG, PNG, WEBP — max 2 Mo.</div>
    @error('photo')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-floppy me-1"></i>Enregistrer
                </button>

            </form>
        </div>
    </div>

</div>
<script>
function previewPhoto(input) {
    const box = document.getElementById('previewBox');
    const img = document.getElementById('previewImg');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            img.src = e.target.result;
            box.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        box.style.display = 'none';
    }
}
</script>
@endsection