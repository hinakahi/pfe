{{-- resources/views/foyer/annonces/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Créer Annonce')
@section('page-title', 'Créer une nouvelle Annonce')

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

    .form-card .card-body {
        padding: 2rem;
    }

    .form-label {
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }

    .form-control {
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        padding: 0.7rem 1rem;
        font-size: 0.95rem;
        transition: border-color 0.15s;
    }

    .form-control:focus {
        outline: none;
        border-color: #2979d8;
        box-shadow: 0 0 0 3px rgba(41, 121, 216, 0.1);
    }

    textarea.form-control {
        resize: vertical;
        min-height: 150px;
        font-family: inherit;
    }

    .mb-4 {
        margin-bottom: 1.5rem;
    }

    .text-danger {
        color: #ef4444;
    }

    /* Catégories */
    .cat-options-container {
        display: flex;
        gap: 12px;
        margin-top: 0.75rem;
    }

    .cat-option {
        flex: 1;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 1rem;
        cursor: pointer;
        transition: all 0.18s;
        text-align: center;
    }

    .cat-option:hover {
        border-color: #94a3b8;
    }

    .cat-option input[type="radio"] {
        display: none;
    }

    .cat-option.selected-generale {
        border-color: #1d4ed8;
        background: #eff6ff;
    }

    .cat-option.selected-urgente {
        border-color: #b91c1c;
        background: #fef2f2;
    }

    .cat-option.selected-evenement {
        border-color: #15803d;
        background: #f0fdf4;
    }

    .cat-icon {
        font-size: 1.8rem;
        display: block;
        margin-bottom: 0.4rem;
    }

    .cat-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: #334155;
    }

    .form-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }

    .btn-submit {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 0.7rem 1.5rem;
        border-radius: 10px;
        background: linear-gradient(135deg, #1a4fa0, #2979d8);
        color: #fff;
        font-size: 0.95rem;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: opacity 0.15s;
    }

    .btn-submit:hover {
        opacity: 0.88;
    }

    .btn-cancel {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 0.7rem 1.5rem;
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        background: #fff;
        color: #475569;
        font-size: 0.95rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.15s;
    }

    .btn-cancel:hover {
        border-color: #cbd5e1;
        background: #f8fafc;
    }

    .invalid-feedback {
        color: #ef4444;
        font-size: 0.85rem;
        margin-top: 0.4rem;
        display: block;
    }

    .is-invalid {
        border-color: #ef4444 !important;
    }
</style>

<div class="form-card card">
    <div class="card-body">
        <form method="POST" action="{{ route('foyer.annonces.store') }}">
            @csrf

            {{-- Titre --}}
            <div class="mb-4">
                <label class="form-label">Titre <span class="text-danger">*</span></label>
                <input type="text" name="titre"
                       class="form-control @error('titre') is-invalid @enderror"
                       placeholder="Entrez le titre de l'annonce"
                       value="{{ old('titre') }}" required>
                @error('titre')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Catégorie --}}
            <div class="mb-4">
                <label class="form-label">Catégorie <span class="text-danger">*</span></label>
                <div class="cat-options-container" id="catOptions">
                    <label class="cat-option {{ old('categorie') === 'generale' ? 'selected-generale' : '' }}" id="opt-generale">
                        <input type="radio" name="categorie" value="generale"
                               {{ old('categorie') === 'generale' || !old('categorie') ? 'checked' : '' }}>
                        <span class="cat-icon">ℹ️</span>
                        <span class="cat-label">Générale</span>
                    </label>
                    <label class="cat-option {{ old('categorie') === 'urgente' ? 'selected-urgente' : '' }}" id="opt-urgente">
                        <input type="radio" name="categorie" value="urgente"
                               {{ old('categorie') === 'urgente' ? 'checked' : '' }}>
                        <span class="cat-icon">🚨</span>
                        <span class="cat-label">Urgente</span>
                    </label>
                    <label class="cat-option {{ old('categorie') === 'evenement' ? 'selected-evenement' : '' }}" id="opt-evenement">
                        <input type="radio" name="categorie" value="evenement"
                               {{ old('categorie') === 'evenement' ? 'checked' : '' }}>
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
                          required>{{ old('contenu') }}</textarea>
                @error('contenu')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Destinataire --}}
            <div class="mb-4">
                <label class="form-label">Destinataire <span class="text-danger">*</span></label>
                <input type="text" name="destinataire"
                       class="form-control @error('destinataire') is-invalid @enderror"
                       placeholder="Ex: Tous, Locataires, Propriétaires..."
                       value="{{ old('destinataire') }}" required>
                @error('destinataire')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Boutons --}}
            <div class="form-buttons">
                <button type="submit" class="btn-submit">
                    <i class="bi bi-check-lg"></i> Publier l'annonce
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