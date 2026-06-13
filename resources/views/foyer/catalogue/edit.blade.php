@extends('layouts.app')
@section('title', 'Modifier un Article')

@section('sidebar')
    @include('foyer.partials._sidebar')
@endsection

@section('page-title')
    Modifier — {{ $catalogue->nom_article }}
@endsection

@section('content')

<div class="card" style="max-width:700px;">
    <div class="card-body">
        <form method="POST" action="{{ route('foyer.catalogue.modifier', $catalogue) }}"
            enctype="multipart/form-data">
             @csrf

            {{-- Photo actuelle --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Photo de l'article</label>
                @if($catalogue->photo)
                    <div class="mb-2 d-flex align-items-center gap-2">
                        <img src="{{ Storage::url($catalogue->photo) }}"
                             style="width:70px;height:70px;object-fit:cover;border-radius:8px;">
                        <small class="text-muted">Photo actuelle</small>
                    </div>
                @endif
                <input type="file" name="photo"
                       class="form-control @error('photo') is-invalid @enderror"
                       accept="image/jpeg,image/png"
                       onchange="previewPhoto(this)">
                <small class="text-muted">Laisser vide pour conserver la photo actuelle</small>
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
                <input type="text" name="nom_article"
                       class="form-control @error('nom_article') is-invalid @enderror"
                       value="{{ old('nom_article', $catalogue->nom_article) }}" required>
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
                    <option value="">-- Choisir une catégorie --</option>

                    <option value="fastfood"
                        {{ old('categorie', $catalogue->categorie) == 'fastfood' ? 'selected' : '' }}>
                        Fast Food
                    </option>

                    <option value="magasin"
                        {{ old('categorie', $catalogue->categorie) == 'magasin' ? 'selected' : '' }}>
                        Magasin
                    </option>

                    <option value="cafeteria"
                        {{ old('categorie', $catalogue->categorie) == 'cafeteria' ? 'selected' : '' }}>
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
                <textarea name="description" rows="3"
                          class="form-control @error('description') is-invalid @enderror">{{ old('description', $catalogue->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Prix + Stock --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">
                        Prix (DA) <span class="text-danger">*</span>
                    </label>
                    <input type="number" name="prix" step="0.01" min="0"
                           class="form-control @error('prix') is-invalid @enderror"
                           value="{{ old('prix', $catalogue->prix) }}" required>
                    @error('prix')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">
                        Stock <span class="text-danger">*</span>
                    </label>
                    <input type="number" name="stock" min="0"
                           class="form-control @error('stock') is-invalid @enderror"
                           value="{{ old('stock', $catalogue->stock) }}" required>
                    @error('stock')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Disponible --}}
            <div class="mb-4">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox"
                           name="disponible" id="disponible" value="1"
                           {{ old('disponible', $catalogue->disponible) ? 'checked' : '' }}>
                    <label class="form-check-label" for="disponible">
                        Article disponible
                    </label>
                </div>
            </div>

            {{-- Divider --}}
            <hr class="my-4">

            {{-- Date de Péremption --}}
            <div class="mb-3">
                <label for="date_peremption" class="form-label fw-semibold">
                    <i class="bi bi-calendar-event text-danger me-1"></i>
                    Date de Péremption
                </label>
                @php
                    $datePeremption = old('date_peremption',
                        $catalogue->date_peremption
                            ? \Carbon\Carbon::parse($catalogue->date_peremption)->format('Y-m-d')
                            : ''
                    );
                @endphp
                <input type="date" name="date_peremption" id="date_peremption"
                       class="form-control @error('date_peremption') is-invalid @enderror"
                       value="{{ $datePeremption }}">
                @error('date_peremption')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted">Laissez vide si l'article ne périme pas</small>
            </div>

            {{-- Promotion Section --}}
            <div class="alert alert-light border-2 border-warning mb-3">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox"
                           name="promo_active" id="promo_active" value="1"
                           {{ old('promo_active', $catalogue->promo_active) ? 'checked' : '' }}
                           onchange="togglePromoFields()">
                    <label class="form-check-label fw-semibold" for="promo_active">
                        <i class="bi bi-star-fill text-warning me-1"></i>
                        Activer une promotion
                    </label>
                </div>
            </div>

            {{-- Champs Promo (masqués par défaut) --}}
            <div id="promoFields" style="display: {{ old('promo_active', $catalogue->promo_active) ? 'block' : 'none' }};">

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="prix_promo" class="form-label fw-semibold">
                            Prix Promo (DA)
                        </label>
                        <input type="number" name="prix_promo" id="prix_promo" step="0.01" min="0"
                               class="form-control @error('prix_promo') is-invalid @enderror"
                               value="{{ old('prix_promo', $catalogue->prix_promo) }}"
                               onchange="calculEconomie()">
                        @error('prix_promo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Économie</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="economie" readonly style="background-color: #f0f8f0;">
                            <span class="input-group-text">DA</span>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="promo_remarque" class="form-label fw-semibold">
                        Remarque/Description de la Promo
                    </label>
                    <textarea name="promo_remarque" id="promo_remarque" rows="2"
                              class="form-control @error('promo_remarque') is-invalid @enderror"
                              placeholder="Ex: -20%, Offre limitée, Nouvelle saveur...">{{ old('promo_remarque', $catalogue->promo_remarque) }}</textarea>
                    @error('promo_remarque')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="promo_date_fin" class="form-label fw-semibold">
                        Date de Fin de la Promotion
                    </label>
                    @php
                        $promoDateFin = old('promo_date_fin',
                            $catalogue->promo_date_fin
                                ? \Carbon\Carbon::parse($catalogue->promo_date_fin)->format('Y-m-d')
                                : ''
                        );
                    @endphp
                    <input type="date" name="promo_date_fin" id="promo_date_fin"
                           class="form-control @error('promo_date_fin') is-invalid @enderror"
                           value="{{ $promoDateFin }}">
                    @error('promo_date_fin')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Optionnel - Laissez vide pour une promotion sans limite de durée</small>
                </div>

            </div>

            {{-- Boutons --}}
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i>Enregistrer les modifications
                </button>
                <a href="{{ route('foyer.catalogue.index') }}"
                   class="btn btn-outline-secondary">Annuler</a>
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
        reader.onload = e => {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function togglePromoFields() {
    const promoActive = document.getElementById('promo_active').checked;
    document.getElementById('promoFields').style.display = promoActive ? 'block' : 'none';
    if (promoActive) calculEconomie();
}

function calculEconomie() {
    const prix      = parseFloat(document.querySelector('input[name="prix"]').value) || 0;
    const prixPromo = parseFloat(document.getElementById('prix_promo').value) || 0;
    document.getElementById('economie').value = (prix - prixPromo).toFixed(2);
}

document.addEventListener('DOMContentLoaded', calculEconomie);
document.addEventListener('DOMContentLoaded', function () {
    calculEconomie();

    const stockInput       = document.querySelector('input[name="stock"]');
    const toggleDisponible = document.getElementById('disponible');
    const togglePromo      = document.getElementById('promo_active');

    function checkStock() {
        const stock = parseInt(stockInput.value) || 0;

        if (stock === 0) {
            toggleDisponible.checked  = false;
            toggleDisponible.disabled = true;
            togglePromo.checked       = false;
            togglePromo.disabled      = true;
            document.getElementById('promoFields').style.display = 'none';
        } else {
            toggleDisponible.disabled = false;
            togglePromo.disabled      = false;
            // ✅ Ne pas toucher promoFields ici, togglePromoFields() s'en charge
        }
    }

    stockInput.addEventListener('input', checkStock);
    checkStock();
});
</script>
@endsection

<style>
    .alert-light { background-color: #fffbf0; }
    .form-check-input:checked { background-color: #ffc107; border-color: #ffc107; }
    .form-control[readonly] { cursor: not-allowed; }
</style>

