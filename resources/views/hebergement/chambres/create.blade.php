@extends('layouts.app')
@section('page-title', 'Ajouter une chambre')
@section('content')

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Ajouter une chambre</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('hebergement.chambres.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Numéro de chambre</label>
                        <input type="text" name="numero"
                               class="form-control @error('numero') is-invalid @enderror"
                               value="{{ old('numero') }}" placeholder="ex: A101">
                        @error('numero')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Type</label>
                        <select name="type" class="form-select @error('type') is-invalid @enderror">
                            <option value="">-- Choisir --</option>
                            <option value="individuelle" {{ old('type')=='individuelle'?'selected':'' }}>Individuelle</option>
                            <option value="double" {{ old('type')=='double'?'selected':'' }}>Double</option>
                        </select>
                        @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Bloc</label>
                        <input type="text" name="bloc"
                               class="form-control @error('bloc') is-invalid @enderror"
                               value="{{ old('bloc') }}" placeholder="ex: A, B, C">
                        @error('bloc')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Étage</label>
                        <input type="number" name="etage"
                               class="form-control @error('etage') is-invalid @enderror"
                               value="{{ old('etage', 0) }}" min="0">
                        @error('etage')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Capacité</label>
                        <input type="number" name="capacite"
                               class="form-control @error('capacite') is-invalid @enderror"
                               value="{{ old('capacite', 1) }}" min="1" max="2">
                        @error('capacite')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Enregistrer
                        </button>
                        <a href="{{ route('hebergement.chambres.index') }}" class="btn btn-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection