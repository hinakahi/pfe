{{-- resources/views/foyer/annonces/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Modifier Annonce')
@section('page-title', 'Modifier l\'Annonce')

@section('sidebar')
    @include('foyer.partials._sidebar')
@endsection

@section('content')

<style>
    .form-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.07);
        border: none;
        max-width: 680px;
        margin: 0 auto;
    }
    .form-card .card-body { padding: 2rem; }
    .form-label { font-weight: 600; color: #1e293b; margin-bottom: 0.5rem; font-size: 0.95rem; }
    .form-control {
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        padding: 0.7rem 1rem;
        font-size: 0.95rem;
        transition: border-color 0.15s;
        width: 100%;
        box-sizing: border-box;
    }
    .form-control:focus { outline: none; border-color: #2979d8; box-shadow: 0 0 0 3px rgba(41,121,216,0.1); }
    textarea.form-control { resize: vertical; min-height: 150px; font-family: inherit; }
    .mb-4 { margin-bottom: 1.5rem; }
    .text-danger { color: #ef4444; }

    /* Catégories */
    .cat-options-container { display: flex; gap: 12px; margin-top: 0.75rem; }
    .cat-option {
        flex: 1;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 1rem;
        cursor: pointer;
        transition: all 0.18s;
        text-align: center;
    }
    .cat-option:hover { border-color: #94a3b8; }
    .cat-option input[type="radio"] { display: none; }
    .cat-option.selected-generale  { border-color: #1d4ed8; background: #eff6ff; }
    .cat-option.selected-urgente   { border-color: #b91c1c; background: #fef2f2; }
    .cat-option.selected-evenement { border-color: #15803d; background: #f0fdf4; }
    .cat-icon  { font-size: 1.8rem; display: block; margin-bottom: 0.4rem; }
    .cat-label { font-size: 0.85rem; font-weight: 600; color: #334155; }

    /* Destinataire — cartes étudiantes */
    .dest-options-container { display: flex; gap: 10px; margin-top: 0.75rem; flex-wrap: wrap; }
    .dest-option {
        flex: 1;
        min-width: 130px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 0.85rem 0.75rem;
        cursor: pointer;
        transition: all 0.18s;
        text-align: center;
        position: relative;
    }
    .dest-option:hover { border-color: #94a3b8; background: #f8fafc; }
    .dest-option input[type="radio"] { display: none; }
    .dest-option.selected {
        border-color: #7c3aed;
        background: #f5f3ff;
    }
    .dest-option.selected .dest-label { color: #6d28d9; }
    .dest-icon  { font-size: 1.6rem; display: block; margin-bottom: 0.35rem; }
    .dest-label { font-size: 0.82rem; font-weight: 600; color: #475569; }
    .dest-check {
        display: none;
        position: absolute;
        top: 6px;
        right: 8px;
        width: 18px;
        height: 18px;
        background: #7c3aed;
        border-radius: 50%;
        align-items: center;
        justify-content: center;
    }
    .dest-option.selected .dest-check { display: flex; }
    .dest-check i { font-size: 10px; color: #fff; }

    /* Boutons */
    .form-buttons { display: flex; gap: 1rem; margin-top: 2rem; }
    .btn-submit {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 0.7rem 1.5rem; border-radius: 10px;
        background: linear-gradient(135deg, #1a4fa0, #2979d8);
        color: #fff; font-size: 0.95rem; font-weight: 600;
        border: none; cursor: pointer; transition: opacity 0.15s;
    }
    .btn-submit:hover { opacity: 0.88; }
    .btn-cancel {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 0.7rem 1.5rem; border-radius: 10px;
        border: 1.5px solid #e2e8f0; background: #fff;
        color: #475569; font-size: 0.95rem; font-weight: 600;
        text-decoration: none; transition: all 0.15s;
    }
    .btn-cancel:hover { border-color: #cbd5e1; background: #f8fafc; }
    .invalid-feedback { color: #ef4444; font-size: 0.85rem; margin-top: 0.4rem; display: block; }
    .is-invalid { border-color: #ef4444 !important; }
</style>

<div class="form-card card">
    <div class="card-body">
        <form method="POST" action="{{ route('foyer.annonces.update', $annonce) }}">
            

            {{-- Titre --}}
            <div class="mb-4">
                <label class="form-label">Titre <span class="text-danger">*</span></label>
                <input type="text" name="titre"
                       class="form-control @error('titre') is-invalid @enderror"
                       placeholder="Entrez le titre de l'annonce"
                       value="{{ old('titre', $annonce->titre) }}" required>
                @error('titre')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Catégorie --}}
            <div class="mb-4">
                <label class="form-label">Catégorie <span class="text-danger">*</span></label>
                <div class="cat-options-container" id="catOptions">
                    <label class="cat-option {{ old('categorie', $annonce->categorie) === 'generale' ? 'selected-generale' : '' }}" id="opt-generale">
                        <input type="radio" name="categorie" value="generale"
                               {{ old('categorie', $annonce->categorie) === 'generale' ? 'checked' : '' }}>
                        <span class="cat-icon">ℹ️</span>
                        <span class="cat-label">Générale</span>
                    </label>
                    <label class="cat-option {{ old('categorie', $annonce->categorie) === 'urgente' ? 'selected-urgente' : '' }}" id="opt-urgente">
                        <input type="radio" name="categorie" value="urgente"
                               {{ old('categorie', $annonce->categorie) === 'urgente' ? 'checked' : '' }}>
                        <span class="cat-icon">🚨</span>
                        <span class="cat-label">Urgente</span>
                    </label>
                    <label class="cat-option {{ old('categorie', $annonce->categorie) === 'evenement' ? 'selected-evenement' : '' }}" id="opt-evenement">
                        <input type="radio" name="categorie" value="evenement"
                               {{ old('categorie', $annonce->categorie) === 'evenement' ? 'checked' : '' }}>
                        <span class="cat-icon">📅</span>
                        <span class="cat-label">Événement</span>
                    </label>
                </div>
                @error('categorie')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Contenu --}}
            <div class="mb-4">
                <label class="form-label">Contenu <span class="text-danger">*</span></label>
                <textarea name="contenu"
                          class="form-control @error('contenu') is-invalid @enderror"
                          placeholder="Décrivez votre annonce ici..."
                          required>{{ old('contenu', $annonce->contenu) }}</textarea>
                @error('contenu')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Destinataire fixe --}}
            <input type="hidden" name="destinataire" value="etudiante">

            {{-- Boutons --}}
            <div class="form-buttons">
                <button type="submit" class="btn-submit">
                    <i class="bi bi-check-lg"></i> Enregistrer les modifications
                </button>
                <a href="{{ route('foyer.annonces.index') }}" class="btn-cancel">
                    <i class="bi bi-x-lg"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
// Catégorie
document.querySelectorAll('#catOptions .cat-option input').forEach(radio => {
    radio.addEventListener('change', function () {
        document.querySelectorAll('#catOptions .cat-option').forEach(opt => {
            opt.className = 'cat-option';
        });
        this.closest('.cat-option').classList.add('selected-' + this.value);
    });
});

// Destinataire
document.querySelectorAll('#destOptions .dest-option input').forEach(radio => {
    radio.addEventListener('change', function () {
        document.querySelectorAll('#destOptions .dest-option').forEach(opt => {
            opt.classList.remove('selected');
        });
        this.closest('.dest-option').classList.add('selected');
    });
});
</script>
@endsection