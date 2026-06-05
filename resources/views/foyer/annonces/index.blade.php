{{-- resources/views/foyer/annonces/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Annonces')
@section('page-title', 'Gestion des Annonces')

@section('sidebar')
    @include('foyer.partials._sidebar')
@endsection

@section('content')

<style>
    .ann-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.07);
        overflow: hidden;
        border: none;
    }
    .ann-header {
        padding: 1.2rem 1.5rem;
        border-bottom: 1.5px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #f8fafc;
    }
    .ann-item {
        padding: 1.2rem 1.5rem;
        border-bottom: 1px solid #f1f5f9;
        transition: background 0.15s;
    }
    .ann-item:last-child { border-bottom: none; }
    .ann-item:hover { background: #f8fafc; }

    .cat-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 600;
    }
    .cat-generale  { background: #dbeafe; color: #1d4ed8; }
    .cat-urgente   { background: #fee2e2; color: #b91c1c; }
    .cat-evenement { background: #dcfce7; color: #15803d; }

    .btn-new {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 0.5rem 1.2rem;
        border-radius: 10px;
        background: linear-gradient(135deg, #1a4fa0, #2979d8);
        color: #fff;
        font-size: 0.88rem;
        font-weight: 600;
        text-decoration: none;
        border: none;
        transition: opacity 0.15s;
    }
    .btn-new:hover { opacity: 0.88; color: #fff; }

    .btn-delete {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 0.3rem 0.75rem;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 500;
        border: 1.5px solid #ef4444;
        color: #b91c1c;
        background: #fef2f2;
        cursor: pointer;
        transition: all 0.15s;
    }
    .btn-delete:hover { background: #fee2e2; }

    .empty-state {
        text-align: center;
        padding: 4rem 1rem;
        color: #94a3b8;
    }
    .empty-state i { font-size: 3rem; display: block; margin-bottom: 0.75rem; }

    .ann-titre {
        font-weight: 700;
        font-size: 1rem;
        color: #1e293b;
        margin-bottom: 0.3rem;
    }
    .ann-contenu {
        font-size: 0.88rem;
        color: #475569;
        line-height: 1.5;
        margin-bottom: 0.5rem;
    }
    .ann-meta {
        font-size: 0.75rem;
        color: #94a3b8;
        display: flex;
        align-items: center;
        gap: 12px;
    }
</style>

{{-- Flash messages --}}


<div class="ann-card">
    <div class="ann-header">
        <div>
            <span style="font-weight:700; font-size:1rem; color:#1e293b;">
                <i class="bi bi-megaphone-fill me-2 text-primary"></i>Annonces publiées
            </span>
            <span style="font-size:0.82rem; color:#94a3b8; margin-left:8px;">
                {{ $annonces->total() }} annonce(s)
            </span>
        </div>
        <a href="{{ route('foyer.annonces.create') }}" class="btn-new">
            <i class="bi bi-plus-lg"></i> Nouvelle annonce
        </a>
    </div>

    @forelse($annonces as $annonce)
        <div class="ann-item">
            <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:1rem;">
                <div style="flex:1;">
                    {{-- Catégorie --}}
                    @php
                        $catInfo = match($annonce->categorie) {
                            'urgente'   => ['bi-exclamation-triangle-fill', 'Urgente',   'urgente'],
                            'evenement' => ['bi-calendar-event-fill',       'Événement', 'evenement'],
                            default     => ['bi-info-circle-fill',          'Générale',  'generale'],
                        };
                    @endphp
                    <span class="cat-badge cat-{{ $catInfo[2] }} mb-2">
                        <i class="bi {{ $catInfo[0] }}"></i> {{ $catInfo[1] }}
                    </span>

                    <div class="ann-titre">{{ $annonce->titre }}</div>
                    <div class="ann-contenu">{{ Str::limit($annonce->contenu, 200) }}</div>
                    <div class="ann-meta">
                        <span><i class="bi bi-person me-1"></i>{{ $annonce->user->name ?? 'Foyer' }}</span>
                        <span><i class="bi bi-clock me-1"></i>{{ $annonce->created_at->diffForHumans() }}</span>
                        <span><i class="bi bi-people me-1"></i>Destinataire : {{ $annonce->destinataire }}</span>
                    </div>
                </div>
                <div style="display:flex; gap:8px; align-items:center;">
    {{-- Modifier --}}
    <a href="{{ route('foyer.annonces.edit', $annonce) }}"
       style="display:inline-flex; align-items:center; gap:4px; padding:0.3rem 0.75rem;
              border-radius:8px; font-size:0.8rem; font-weight:500;
              border:1.5px solid #2979d8; color:#1a4fa0; background:#eff6ff;
              text-decoration:none; transition:all 0.15s;">
        <i class="bi bi-pencil"></i> Modifier
    </a>

    {{-- Supprimer --}}
    <form method="POST"
          action="{{ route('foyer.annonces.destroy', $annonce) }}"
          onsubmit="return confirm('Supprimer cette annonce ?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn-delete">
            <i class="bi bi-trash"></i> Supprimer
        </button>
    </form>
</div>
4. Créez resources/views/foyer/annonces/edit.blade.php — c'est identique à create.blade.php avec juste les valeurs pré-remplies :

blade
@extends('layouts.app')
@section('title', 'Modifier Annonce')
@section('page-title', 'Modifier l\'Annonce')
@section('sidebar')
    @include('foyer.partials._sidebar')
@endsection

@section('content')

{{-- Réutilisez le même style que create.blade.php --}}
<div class="form-card card">
    <div class="card-body">
        <form method="POST" action="{{ route('foyer.annonces.update', $annonce) }}">
            @csrf

            {{-- Titre --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">Titre <span class="text-danger">*</span></label>
                <input type="text" name="titre"
                       class="form-control @error('titre') is-invalid @enderror"
                       value="{{ old('titre', $annonce->titre) }}" required>
                @error('titre')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Catégorie --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">Catégorie <span class="text-danger">*</span></label>
                <div style="display:flex; gap:12px;" id="catOptions">
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
            </div>

            {{-- Contenu --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">Contenu <span class="text-danger">*</span></label>
                <textarea name="contenu"
                          class="form-control @error('contenu') is-invalid @enderror"
                          required>{{ old('contenu', $annonce->contenu) }}</textarea>
                @error('contenu')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Boutons --}}
            <div class="d-flex gap-2">
                <button type="submit" class="btn-submit">
                    <i class="bi bi-check-lg"></i> Enregistrer
                </button>
                <a href="{{ route('foyer.annonces.index') }}"
                   class="btn btn-outline-secondary" style="border-radius:10px;">Annuler</a>
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

<style>
.form-card { background:#fff; border-radius:16px; box-shadow:0 2px 12px rgba(0,0,0,0.07); border:none; max-width:680px; }
.form-card .card-body { padding:2rem; }
.cat-option { flex:1; border:2px solid #e2e8f0; border-radius:12px; padding:1rem; cursor:pointer; transition:all 0.18s; text-align:center; }
.cat-option:hover { border-color:#94a3b8; }
.cat-option input[type="radio"] { display:none; }
.cat-option.selected-generale  { border-color:#1d4ed8; background:#eff6ff; }
.cat-option.selected-urgente   { border-color:#b91c1c; background:#fef2f2; }
.cat-option.selected-evenement { border-color:#15803d; background:#f0fdf4; }
.cat-icon { font-size:1.8rem; display:block; margin-bottom:0.4rem; }
.cat-label { font-size:0.85rem; font-weight:600; color:#334155; }
.btn-submit { display:inline-flex; align-items:center; gap:6px; padding:0.6rem 1.5rem; border-radius:10px; background:linear-gradient(135deg,#1a4fa0,#2979d8); color:#fff; font-size:0.9rem; font-weight:600; border:none; cursor:pointer; }
.form-control { border-radius:10px; border:1.5px solid #e2e8f0; padding:0.6rem 0.9rem; }
textarea.form-control { resize:vertical; min-height:140px; }
</style>
Faites ces 4 modifications et testez.



Vous avez utilis
                {{-- Supprimer --}}
                <form method="POST"
                      action="{{ route('foyer.annonces.destroy', $annonce) }}"
                      onsubmit="return confirm('Supprimer cette annonce ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-delete">
                        <i class="bi bi-trash"></i> Supprimer
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="empty-state">
            <i class="bi bi-megaphone"></i>
            Aucune annonce publiée pour l'instant.
            <div class="mt-3">
                <a href="{{ route('foyer.annonces.create') }}" class="btn-new">
                    <i class="bi bi-plus-lg"></i> Créer la première annonce
                </a>
            </div>
        </div>
    @endforelse
</div>

{{-- Pagination --}}
@if($annonces->hasPages())
    <div class="mt-3">{{ $annonces->links() }}</div>
@endif

@endsection