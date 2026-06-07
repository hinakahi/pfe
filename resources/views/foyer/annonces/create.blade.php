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
        max-width: 720px;
        margin: 0 auto;
    }
    .form-card .card-body { padding: 2rem; }
    .form-label { font-weight: 600; color: #1e293b; margin-bottom: 0.5rem; font-size: 0.95rem; display: block; }
    .form-control, .form-select {
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        padding: 0.7rem 1rem;
        font-size: 0.95rem;
        width: 100%;
        box-sizing: border-box;
        transition: border-color 0.15s;
    }
    .form-control:focus, .form-select:focus {
        outline: none;
        border-color: #2979d8;
        box-shadow: 0 0 0 3px rgba(41,121,216,0.1);
    }
    textarea.form-control { resize: vertical; min-height: 150px; font-family: inherit; }
    .mb-4 { margin-bottom: 1.5rem; }
    .text-danger { color: #ef4444; }
    .invalid-feedback { color: #ef4444; font-size: 0.85rem; margin-top: 0.4rem; display: block; }
    .is-invalid { border-color: #ef4444 !important; }

    /* Cartes catégorie */
    .cat-options-container { display: flex; gap: 10px; margin-top: 0.75rem; flex-wrap: wrap; }
    .cat-option {
        flex: 1; min-width: 100px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 0.8rem 0.5rem;
        cursor: pointer;
        transition: all 0.18s;
        text-align: center;
    }
    .cat-option input[type="radio"] { display: none; }
    .cat-option:hover { border-color: #94a3b8; }
    .cat-option.selected { border-color: #2979d8; background: #eff6ff; }
    .cat-icon  { font-size: 1.5rem; display: block; margin-bottom: 0.3rem; }
    .cat-label { font-size: 0.8rem; font-weight: 600; color: #334155; }

    /* Cartes urgence */
    .urg-options-container { display: flex; gap: 10px; margin-top: 0.75rem; }
    .urg-option {
        flex: 1;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 0.8rem 0.5rem;
        cursor: pointer;
        transition: all 0.18s;
        text-align: center;
    }
    .urg-option input[type="radio"] { display: none; }
    .urg-option:hover { border-color: #94a3b8; }
    .urg-option.selected-general        { border-color: #6c757d; background: #f8f9fa; }
    .urg-option.selected-urgent         { border-color: #dc3545; background: #fef2f2; }
    .urg-option.selected-administration { border-color: #212529; background: #f1f3f5; }
    .urg-icon  { font-size: 1.5rem; display: block; margin-bottom: 0.3rem; }
    .urg-label { font-size: 0.8rem; font-weight: 600; color: #334155; }

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
                    @php
                        $cats = [
                            'generale'    => [ 'label' => 'Générale'],
                            
                            'promotion'   => [ 'label' => 'Promotion'],
                        ];
                    @endphp
                    @foreach($cats as $val => $info)
                    <label class="cat-option {{ old('categorie', 'generale') === $val ? 'selected' : '' }}">
                        <input type="radio" name="categorie" value="{{ $val }}"
                               {{ old('categorie', 'generale') === $val ? 'checked' : '' }}>
                        
                        <span class="cat-label">{{ $info['label'] }}</span>
                    </label>
                    @endforeach
                </div>
                @error('categorie')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Urgence --}}
            <div class="mb-4">
                <label class="form-label">Niveau d'urgence <span class="text-danger">*</span></label>
                <div class="urg-options-container" id="urgOptions">
                    <label class="urg-option {{ old('urgence', 'general') === 'general' ? 'selected-general' : '' }}">
                        <input type="radio" name="urgence" value="general"
                               {{ old('urgence', 'general') === 'general' ? 'checked' : '' }}>
                        <span class="urg-icon">📌</span>
                        <span class="urg-label">Général</span>
                    </label>
                    <label class="urg-option {{ old('urgence') === 'urgent' ? 'selected-urgent' : '' }}">
                        <input type="radio" name="urgence" value="urgent"
                               {{ old('urgence') === 'urgent' ? 'checked' : '' }}>
                        <span class="urg-icon">🔴</span>
                        <span class="urg-label">Urgent</span>
                    </label>
                  
                </div>
                @error('urgence')<div class="invalid-feedback">{{ $message }}</div>@enderror
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

            {{-- Destinataire caché --}}
            <input type="hidden" name="destinataire" value="etudiantes">

            {{-- Boutons --}}
            <div class="form-buttons">
                <button type="submit" class="btn-submit">Publier l'annonce</button>
                <a href="{{ route('foyer.annonces.index') }}" class="btn-cancel">Annuler</a>
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
        document.querySelectorAll('#catOptions .cat-option').forEach(opt => opt.className = 'cat-option');
        this.closest('.cat-option').classList.add('selected');
    });
});

// Urgence
document.querySelectorAll('#urgOptions .urg-option input').forEach(radio => {
    radio.addEventListener('change', function () {
        document.querySelectorAll('#urgOptions .urg-option').forEach(opt => opt.className = 'urg-option');
        this.closest('.urg-option').classList.add('selected-' + this.value);
    });
});
</script>
@endsection