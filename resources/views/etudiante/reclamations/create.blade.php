@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 650px;">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('etudiante.reclamations.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h4 class="mb-0">Nouvelle réclamation</h4>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-4">
            <form action="{{ route('etudiante.reclamations.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Sujet <span class="text-danger">*</span></label>
                    <input type="text" name="sujet"
                           class="form-control @error('sujet') is-invalid @enderror"
                           value="{{ old('sujet') }}"
                           placeholder="Ex: Problème de chambre, bruit, etc.">
                    @error('sujet')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Message <span class="text-danger">*</span></label>
                    <textarea name="message" rows="6"
                              class="form-control @error('message') is-invalid @enderror"
                              placeholder="Décrivez votre problème en détail...">{{ old('message') }}</textarea>
                    @error('message')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-send me-1"></i> Envoyer la réclamation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection