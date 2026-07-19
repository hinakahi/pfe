@extends('layouts.app')
@section('title', 'Modifier annonce')

@section('sidebar')
    @include('admin.partials._sidebar')
@endsection

@section('page-title', 'Modifier l\'annonce')

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
    .urg-option.selected-general { border-color: #6c757d; background: #f8f9fa; }
    .urg-option.selected-urgent  { border-color: #dc3545; background: #fef2f2; }
    .urg-icon  { font-size: 1.5rem; display: block; margin-bottom: 0.3rem; }
    .urg-label { font-size: 0.8rem; font-weight: 600; color: #334155; }

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
        <form method="POST" action="{{ route('admin.annonces.update', $annonce) }}">
            @csrf @method('PUT')

            {{-- Titre --}}
            <div class="mb-4">
                <label class="form-label">Titre <span class="text-danger">*</span></label>
                <input type="text" name="titre" class="form-control"
                       value="{{ old('titre', $annonce->titre) }}" required>
            </div>

            {{-- Destinataires --}}
            <div class="mb-4">
                <label class="form-label">Destinataires <span class="text-danger">*</span></label>
                <select name="destinataire" class="form-select" required>
                    <option value="tous" {{ old('destinataire', $annonce->destinataire) === 'tous' ? 'selected' : '' }}>Tous les utilisateurs</option>
                    <option value="etudiantes" {{ old('destinataire', $annonce->destinataire) === 'etudiantes' ? 'selected' : '' }}>Étudiantes uniquement</option>
                    <option value="staff" {{ old('destinataire', $annonce->destinataire) === 'staff' ? 'selected' : '' }}>Staff uniquement</option>
                </select>
            </div>

            {{-- Urgence --}}
            <div class="mb-4">
                <label class="form-label">Niveau d'urgence <span class="text-danger">*</span></label>
                <div class="urg-options-container" id="urgOptions">
                    <label class="urg-option {{ old('urgence', $annonce->urgence) === 'general' ? 'selected-general' : '' }}">
                        <input type="radio" name="urgence" value="general"
                               {{ old('urgence', $annonce->urgence) === 'general' ? 'checked' : '' }}>
                        <span class="urg-icon">📌</span>
                        <span class="urg-label">Général</span>
                    </label>
                    <label class="urg-option {{ old('urgence', $annonce->urgence) === 'urgent' ? 'selected-urgent' : '' }}">
                        <input type="radio" name="urgence" value="urgent"
                               {{ old('urgence', $annonce->urgence) === 'urgent' ? 'checked' : '' }}>
                        <span class="urg-icon">🔴</span>
                        <span class="urg-label">Urgent</span>
                    </label>
                </div>
            </div>

            {{-- Contenu --}}
            <div class="mb-4">
                <label class="form-label">Contenu <span class="text-danger">*</span></label>
                <textarea name="contenu" rows="6" class="form-control" required>{{ old('contenu', $annonce->contenu) }}</textarea>
            </div>

            {{-- Boutons --}}
            <div class="form-buttons">
                <button type="submit" class="btn-submit">
                    <i class="bi bi-check-lg me-1"></i>Enregistrer
                </button>
                <a href="{{ route('admin.annonces.index') }}" class="btn-cancel">Annuler</a>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.querySelectorAll('#urgOptions .urg-option input').forEach(radio => {
    radio.addEventListener('change', function () {
        document.querySelectorAll('#urgOptions .urg-option').forEach(opt => opt.className = 'urg-option');
        this.closest('.urg-option').classList.add('selected-' + this.value);
    });
});
</script>
@endsection