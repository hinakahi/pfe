@extends('layouts.app')
@section('title', 'Modifier Annonce')
@section('page-title', 'Modifier l\'Annonce')

@section('sidebar')
    @include('foyer.partials._sidebar')
@endsection

@section('content')

<style>
.form-card { background:#fff; border-radius:16px; box-shadow:0 2px 12px rgba(0,0,0,0.07); border:none; max-width:680px; }
.form-card .card-body { padding:2rem; }
.cat-option { flex:1; border:2px solid #e2e8f0; border-radius:12px; padding:1rem; cursor:pointer; transition:all 0.18s; text-align:center; display:flex; flex-direction:column; align-items:center; }
.cat-option:hover { border-color:#94a3b8; }
.cat-option input[type="radio"] { display:none; }
.cat-option.selected-generale  { border-color:#1d4ed8; background:#eff6ff; }
.cat-option.selected-urgente   { border-color:#b91c1c; background:#fef2f2; }
.cat-option.selected-evenement { border-color:#15803d; background:#f0fdf4; }
.cat-icon  { font-size:1.8rem; display:block; margin-bottom:0.4rem; }
.cat-label { font-size:0.85rem; font-weight:600; color:#334155; }
.btn-submit {
    display:inline-flex; align-items:center; gap:6px;
    padding:0.6rem 1.5rem; border-radius:10px;
    background:linear-gradient(135deg,#1a4fa0,#2979d8);
    color:#fff; font-size:0.9rem; font-weight:600;
    border:none; cursor:pointer; transition:opacity 0.15s;
}
.btn-submit:hover { opacity:0.88; }
.form-control { border-radius:10px; border:1.5px solid #e2e8f0; padding:0.6rem 0.9rem; font-size:0.92rem; }
.form-control:focus { border-color:#2979d8; box-shadow:0 0 0 3px rgba(41,121,216,0.1); }
textarea.form-control { resize:vertical; min-height:140px; }
</style>

<div class="form-card card">
    <div class="card-body">
        <form method="POST" action="{{ route('foyer.annonces.update', $annonce) }}">
            @csrf

            {{-- Titre --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">
                    Titre <span class="text-danger">*</span>
                </label>
                <input type="text" name="titre"
                       class="form-control @error('titre') is-invalid @enderror"
                       value="{{ old('titre', $annonce->titre) }}"
                       placeholder="Titre de l'annonce..."
                       required>
                @error('titre')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Catégorie --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">
                    Catégorie <span class="text-danger">*</span>
                </label>
                <div style="display:flex; gap:12px;" id="catOptions">
                    {{-- Générale --}}
                    <label class="cat-option {{ old('categorie', $annonce->categorie) === 'generale' ? 'selected-generale' : '' }}">
                        <input type="radio" name="categorie" value="generale"
                               {{ old('categorie', $annonce->categorie) === 'generale' ? 'checked' : '' }}>
                        <span class="cat-icon">ℹ️</span>
                        <span class="cat-label">Générale</span>
                    </label>

                    {{-- Urgente --}}
                    <label class="cat-option {{ old('categorie', $annonce->categorie) === 'urgente' ? 'selected-urgente' : '' }}">
                        <input type="radio" name="categorie" value="urgente"
                               {{ old('categorie', $annonce->categorie) === 'urgente' ? 'checked' : '' }}>
                        <span class="cat-icon">🚨</span>
                        <span class="cat-label">Urgente</span>
                    </label>

                    {{-- Événement --}}
                    <label class="cat-option {{ old('categorie', $annonce->categorie) === 'evenement' ? 'selected-evenement' : '' }}">
                        <input type="radio" name="categorie" value="evenement"
                               {{ old('categorie', $annonce->categorie) === 'evenement' ? 'checked' : '' }}>
                        <span class="cat-icon">📅</span>
                        <span class="cat-label">Événement</span>
                    </label>
                </div>
                @error('categorie')
                    <div class="text-danger mt-1" style="font-size:0.85rem;">{{ $message }}</div>
                @enderror
            </div>

            {{-- Contenu --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">
                    Contenu <span class="text-danger">*</span>
                </label>
                <textarea name="contenu"
                          class="form-control @error('contenu') is-invalid @enderror"
                          placeholder="Contenu de l'annonce..."
                          required>{{ old('contenu', $annonce->contenu) }}</textarea>
                @error('contenu')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Boutons --}}
            <div class="d-flex gap-2">
                <button type="submit" class="btn-submit">
                    <i class="bi bi-check-lg"></i> Enregistrer les modifications
                </button>
                <a href="{{ route('foyer.annonces.index') }}"
                   class="btn btn-outline-secondary" style="border-radius:10px;">
                   Annuler
                </a>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.querySelectorAll('#catOptions .cat-option input').forEach(radio => {
    radio.addEventListener('change', function () {
        document.querySelectorAll('#catOptions .cat-option').forEach(opt => {
            opt.className = 'cat-option';
        });
        this.closest('.cat-option').classList.add('selected-' + this.value);
    });
});
</script>
@endsection