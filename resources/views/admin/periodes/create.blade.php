@extends('layouts.app')
@section('title', 'Nouvelle période')

@section('sidebar')
    @include('admin.partials._sidebar')
@endsection

@section('page-title', 'Nouvelle période')

@section('content')
<div class="card" style="max-width:560px">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.periodes.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Libellé</label>
                <input type="text" name="libelle" class="form-control @error('libelle') is-invalid @enderror"
                       value="{{ old('libelle') }}" placeholder="ex: Renouvellement 2025-2026" required>
                @error('libelle')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Type</label>
                <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                    <option value="">-- Choisir --</option>
                    <option value="renouvellement" {{ old('type')=='renouvellement' ? 'selected' : '' }}>Renouvellement</option>
                    <option value="changement"     {{ old('type')=='changement' ? 'selected' : '' }}>Changement</option>
                </select>
                @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="row mb-4">
                <div class="col">
                    <label class="form-label fw-semibold">Date début</label>
                    <input type="date" name="date_debut" class="form-control @error('date_debut') is-invalid @enderror"
                           value="{{ old('date_debut') }}" required>
                    @error('date_debut')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col">
                    <label class="form-label fw-semibold">Date fin</label>
                    <input type="date" name="date_fin" class="form-control @error('date_fin') is-invalid @enderror"
                           value="{{ old('date_fin') }}" required>
                    @error('date_fin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            
            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Créer et notifier</button>
                <a href="{{ route('admin.periodes.index') }}" class="btn btn-outline-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection