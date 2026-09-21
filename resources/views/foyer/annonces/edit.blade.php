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
    .cat-label { font-size: 0.8rem; font-weight: 600; color: #334155; }

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
    .urg-option.selected-general { border-color: #6c757d; background: #f8f9fa; }
    .urg-option.selected-urgent  { border-color: #dc3545; background: #fef2f2; }
    .urg-icon  { font-size: 1.5rem; display: block; margin-bottom: 0.3rem; }
    .urg-label { font-size: 0.8rem; font-weight: 600; color: #334155; }

    /* ✅ Photos */
    .photo-grid {
        display: flex; flex-wrap: wrap; gap: 10px; margin-top: 0.75rem;
    }
    .photo-thumb {
        position: relative;
        width: 96px; height: 96px;
        border-radius: 10px;
        overflow: hidden;
        border: 1.5px solid #e2e8f0;
    }
    .photo-thumb img {
        width: 100%; height: 100%; object-fit: cover; display: block;
    }
    .photo-remove {
        position: absolute; top: 4px; right: 4px;
        width: 22px; height: 22px;
        border-radius: 50%;
        background: rgba(220,53,69,0.92);
        color: #fff;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
        font-size: 0.85rem;
        line-height: 1;
        transition: background 0.15s;
    }
    .photo-remove:hover { background: #b02a37; }
    .photo-thumb.marked-remove { opacity: 0.35; filter: grayscale(1); }
    .photo-thumb.marked-remove::after {
        content: '✕';
        position: absolute; inset: 0;
        display: flex; align-items: center; justify-content: center;
        color: #dc3545;
        font-size: 2.2rem;
        font-weight: 700;
    }
    .photo-size {
        position: absolute; bottom: 4px; left: 4px;
        background: rgba(0,0,0,0.65);
        color: #fff;
        font-size: 0.65rem;
        padding: 1px 5px;
        border-radius: 4px;
    }

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
        <form method="POST"
              action="{{ route('foyer.annonces.update', $annonce) }}"
              enctype="multipart/form-data">
            @csrf
            @method('PUT')

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
                    @php
                        $cats = [
                            'generale'  => 'Générale',
                            'promotion' => 'Promotion',
                        ];
                        $currentCat = old('categorie', $annonce->categorie);
                    @endphp
                    @foreach($cats as $val => $label)
                    <label class="cat-option {{ $currentCat === $val ? 'selected' : '' }}">
                        <input type="radio" name="categorie" value="{{ $val }}"
                               {{ $currentCat === $val ? 'checked' : '' }}>
                        <span class="cat-label">{{ $label }}</span>
                    </label>
                    @endforeach
                </div>
                @error('categorie')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Urgence --}}
            <div class="mb-4">
                <label class="form-label">Niveau d'urgence <span class="text-danger">*</span></label>
                @php $currentUrg = old('urgence', $annonce->urgence ?? 'general'); @endphp
                <div class="urg-options-container" id="urgOptions">
                    <label class="urg-option {{ $currentUrg === 'general' ? 'selected-general' : '' }}">
                        <input type="radio" name="urgence" value="general"
                               {{ $currentUrg === 'general' ? 'checked' : '' }}>
                        <span class="urg-icon">📌</span>
                        <span class="urg-label">Général</span>
                    </label>
                    <label class="urg-option {{ $currentUrg === 'urgent' ? 'selected-urgent' : '' }}">
                        <input type="radio" name="urgence" value="urgent"
                               {{ $currentUrg === 'urgent' ? 'checked' : '' }}>
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
                          required>{{ old('contenu', $annonce->contenu) }}</textarea>
                @error('contenu')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- ✅ PHOTOS --}}
            <div class="mb-4">
                <label class="form-label">
                    Photos
                    <small class="text-muted fw-normal" style="font-size:.78rem;">
                        (max 5 · 3 Mo chacune · JPG, PNG, WebP)
                    </small>
                </label>

                {{-- Photos existantes --}}
                @if(!empty($annonce->photos))
                    <div class="photo-grid" id="existingPhotos">
                        @foreach($annonce->photos as $photo)
                            <div class="photo-thumb" data-photo="{{ $photo }}">
                                <img src="{{ asset('storage/'.$photo) }}" alt="Photo">
                                <div class="photo-remove" title="Supprimer cette photo">
                                    <i class="bi bi-x-lg"></i>
                                </div>
                                <input type="checkbox"
                                       name="remove_photos[]"
                                       value="{{ $photo }}"
                                       class="remove-photo-input"
                                       hidden>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Nouvelle sélection --}}
                <input type="file"
                       name="photos[]"
                       id="photosInput"
                       class="form-control mt-3 @error('photos.*') is-invalid @enderror"
                       accept="image/jpeg,image/png,image/webp"
                       multiple>

                @error('photos.*')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                {{-- Aperçu nouvelles photos --}}
                <div class="photo-grid" id="photosPreview"></div>

                @if(!empty($annonce->photos))
                    <small class="text-muted d-block mt-2" style="font-size:.78rem;">
                        <i class="bi bi-info-circle me-1"></i>
                        Cliquez sur la croix rouge pour supprimer une photo existante.
                    </small>
                @endif
            </div>

            <input type="hidden" name="destinataire" value="etudiantes">

            {{-- Boutons --}}
            <div class="form-buttons">
                <button type="submit" class="btn-submit">Enregistrer les modifications</button>
                <a href="{{ route('foyer.annonces.index') }}" class="btn-cancel">Annuler</a>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
/* ----- Catégorie ----- */
document.querySelectorAll('#catOptions .cat-option input').forEach(radio => {
    radio.addEventListener('change', function () {
        document.querySelectorAll('#catOptions .cat-option').forEach(opt => opt.className = 'cat-option');
        this.closest('.cat-option').classList.add('selected');
    });
});

/* ----- Urgence ----- */
document.querySelectorAll('#urgOptions .urg-option input').forEach(radio => {
    radio.addEventListener('change', function () {
        document.querySelectorAll('#urgOptions .urg-option').forEach(opt => opt.className = 'urg-option');
        this.closest('.urg-option').classList.add('selected-' + this.value);
    });
});

/* ----- Suppression photos existantes ----- */
document.querySelectorAll('#existingPhotos .photo-remove').forEach(btn => {
    btn.addEventListener('click', function () {
        const thumb = this.closest('.photo-thumb');
        const input = thumb.querySelector('.remove-photo-input');
        input.checked = !input.checked;
        thumb.classList.toggle('marked-remove', input.checked);
    });
});

/* ----- Aperçu nouvelles photos ----- */
document.getElementById('photosInput')?.addEventListener('change', function (e) {
    const preview = document.getElementById('photosPreview');
    preview.innerHTML = '';

    const files = [...e.target.files];

    if (files.length > 5) {
        alert('Maximum 5 photos autorisées.');
        e.target.value = '';
        return;
    }

    files.forEach(file => {
        if (file.size > 3 * 1024 * 1024) {
            alert(`"${file.name}" dépasse 3 Mo.`);
            return;
        }

        const thumb = document.createElement('div');
        thumb.className = 'photo-thumb';

        const img = document.createElement('img');
        img.src = URL.createObjectURL(file);

        const size = document.createElement('span');
        size.className = 'photo-size';
        size.textContent = (file.size / 1024).toFixed(0) + ' Ko';

        thumb.appendChild(img);
        thumb.appendChild(size);
        preview.appendChild(thumb);
    });
});
</script>
@endsection