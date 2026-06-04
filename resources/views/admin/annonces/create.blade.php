@extends('layouts.app')
@section('title', 'Nouvelle annonce')

@section('sidebar')
    @include('admin.partials._sidebar')
@endsection

@section('page-title', 'Nouvelle annonce')

@section('content')
<div class="card" style="max-width:680px">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.annonces.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Titre</label>
                <input type="text" name="titre" class="form-control @error('titre') is-invalid @enderror"
                       value="{{ old('titre') }}" required>
                @error('titre')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Destinataires</label>
                <select name="destinataire" class="form-select" required>
                    <option value="tous">Tous les utilisateurs</option>
                    <option value="etudiantes">Étudiantes uniquement</option>
                    <option value="staff">Staff uniquement</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Contenu</label>
                <textarea name="contenu" rows="6"
                          class="form-control @error('contenu') is-invalid @enderror" required>{{ old('contenu') }}</textarea>
                @error('contenu')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
           
            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary"><i class="bi bi-send me-1"></i>Publier</button>
                <a href="{{ route('admin.annonces.index') }}" class="btn btn-outline-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection