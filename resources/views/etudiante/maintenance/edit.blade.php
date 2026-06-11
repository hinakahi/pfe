@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width: 700px;">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('etudiante.maintenance.index') }}">Maintenance</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('etudiante.maintenance.show', $maintenance) }}">Demande #{{ $maintenance->id }}</a>
            </li>
            <li class="breadcrumb-item active">Modifier</li>
        </ol>
    </nav>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="mb-0 fw-bold">Modifier la demande</h5>
        </div>

        <form action="{{ route('etudiante.maintenance.update', $maintenance) }}" method="POST">
            @csrf @method('PUT')

            <div class="card-body p-4">
                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Chambre <span class="text-danger">*</span></label>
                        <select name="chambre_id" class="form-select @error('chambre_id') is-invalid @enderror" required>
                            <option value="">Sélectionner</option>
                            @foreach($chambres as $chambre)
                                <option value="{{ $chambre->id }}"
                                    {{ old('chambre_id', $maintenance->chambre_id) == $chambre->id ? 'selected' : '' }}>
                                    Chambre {{ $chambre->numero }}
                                </option>
                            @endforeach
                        </select>
                        @error('chambre_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Type <span class="text-danger">*</span></label>
                        <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                            <option value="electricite" {{ old('type', $maintenance->type) === 'electricite' ? 'selected' : '' }}>Électricité</option>
                            <option value="plomberie"   {{ old('type', $maintenance->type) === 'plomberie'   ? 'selected' : '' }}>Plomberie</option>
                            <option value="menuiserie"  {{ old('type', $maintenance->type) === 'menuiserie'  ? 'selected' : '' }}>Menuiserie</option>
                            <option value="autre"       {{ old('type', $maintenance->type) === 'autre'       ? 'selected' : '' }}>Autre</option>
                        </select>
                        @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Urgence <span class="text-danger">*</span></label>
                        <select name="urgence" class="form-select @error('urgence') is-invalid @enderror" required>
                            <option value="normale" {{ old('urgence', $maintenance->urgence) === 'normale' ? 'selected' : '' }}>Normale</option>
                            <option value="urgente" {{ old('urgence', $maintenance->urgence) === 'urgente' ? 'selected' : '' }}>Urgente</option>
                        </select>
                        @error('urgence') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
                        <textarea name="description" rows="5"
                                  class="form-control @error('description') is-invalid @enderror"
                                  placeholder="Décrivez le problème..." required>{{ old('description', $maintenance->description) }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                </div>

                <div class="alert alert-warning mt-4 py-2 small mb-0">
                    <i class="bi bi-info-circle me-1"></i>
                    Seules les demandes <strong>en attente</strong> peuvent être modifiées.
                </div>
            </div>

            <div class="card-footer bg-white border-top py-3 d-flex justify-content-between">
                <a href="{{ route('etudiante.maintenance.show', $maintenance) }}"
                   class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Retour
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i>Enregistrer les modifications
                </button>
            </div>

        </form>
    </div>

</div>
@endsection