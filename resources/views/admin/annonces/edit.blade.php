@extends('layouts.app')
@section('title', 'Modifier annonce')

@section('sidebar')
    @include('admin.partials._sidebar')
@endsection

@section('page-title', 'Modifier l\'annonce')

@section('content')
<div class="card" style="max-width:680px">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.annonces.update', $annonce) }}">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label fw-semibold">Titre</label>
                <input type="text" name="titre" class="form-control"
                       value="{{ old('titre', $annonce->titre) }}" required>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Contenu</label>
                <textarea name="contenu" rows="6" class="form-control" required>{{ old('contenu', $annonce->contenu) }}</textarea>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Enregistrer</button>
                <a href="{{ route('admin.annonces.index') }}" class="btn btn-outline-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection