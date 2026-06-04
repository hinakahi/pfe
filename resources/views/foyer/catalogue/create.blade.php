@extends('layouts.app')
@section('title', 'Ajouter un Article')

@section('sidebar')
    @include('foyer.partials._sidebar')
@endsection

@section('page-title', 'Ajouter un Article')

@section('content')

<div class="card" style="max-width:650px;">
    <div class="card-body">
        <form method="POST" action="{{ route('foyer.catalogue.store') }}"
              enctype="multipart/form-data">
            @csrf

            {{-- Photo --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Photo de l'article</label>
                <input type="file" name="photo"
                       class="form-control @error('photo') is-invalid @enderror"
                       accept="image/jpeg,image/png"
                       onchange="previewPhoto(this)">
                @error('photo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <div class="mt-2">
                    <img id="photoPreview" src="#" alt="Aperçu"
                         style="display:none;width:100px;height:100px;
                                object-fit:cover;border-radius:8px;">
                </div>
            </div>

            {{-- Nom --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">
                    Nom de l'article <span class="text-danger">*</span>
                </label>

                <input type="text"
                       name="nom_article"
                       class="form-control @error('nom_article') is-invalid @enderror"
                       value="{{ old('nom_article') }}"
                       placeholder="ex: Eau minérale 1.5L"
                       required>

                @error('nom_article')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Catégorie --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">
                    Catégorie <span class="text-danger">*</span>
                </label>

                <select name="categorie"
                        class="form-select @error('categorie') is-invalid @enderror"
                        required>
                    <option value="">Choisir une catégorie</option>

                    <option value="fastfood"
                        {{ old('categorie') == 'fastfood' ? 'selected' : '' }}>
                        Fast Food
                    </option>

                    <option value="magasin"
                        {{ old('categorie') == 'magasin' ? 'selected' : '' }}>
                        Magasin
                    </option>

                    <option value="cafeteria"
                        {{ old('categorie') == 'cafeteria' ? 'selected' : '' }}>
                        Cafétéria
                    </option>
                </select>

                @error('categorie')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Description --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Description</label>

                <textarea name="description"
                          rows="3"
                          class="form-control @error('description') is-invalid @enderror"
                          placeholder="Description de l'article…">{{ old('description') }}</textarea>

                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Prix + Stock --}}
            <div class="row mb-3">
                <div class="col">
                    <label class="form-label fw-semibold">
                        Prix (DA) <span class="text-danger">*</span>
                    </label>

                    <input type="number"
                           name="prix"
                           step="0.01"
                           min="0"
                           class="form-control @error('prix') is-invalid @enderror"
                           value="{{ old('prix', 0) }}"
                           required>

                    @error('prix')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col">
                    <label class="form-label fw-semibold">
                        Stock <span class="text-danger">*</span>
                    </label>

                    <input type="number"
                           name="stock"
                           min="0"
                           class="form-control @error('stock') is-invalid @enderror"
                           value="{{ old('stock', 0) }}"
                           required>

                    @error('stock')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Disponible --}}
            <div class="mb-4">
                <div class="form-check form-switch">
                    <input class="form-check-input"
                           type="checkbox"
                           name="disponible"
                           id="disponible"
                           value="1"
                           {{ old('disponible', true) ? 'checked' : '' }}>

                    <label class="form-check-label" for="disponible">
                        Article disponible
                    </label>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i>
                    Enregistrer
                </button>

                <a href="{{ route('foyer.catalogue.index') }}"
                   class="btn btn-outline-secondary">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
function previewPhoto(input) {
    const preview = document.getElementById('photoPreview');

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };

        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection