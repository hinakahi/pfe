@extends('layouts.app')
@section('title', 'Modifier période')

@section('sidebar')
    @include('admin.partials._sidebar')
@endsection

@section('page-title', 'Modifier la période')

@section('content')
<div class="card" style="max-width:700px">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.periodes.update', $periode) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label fw-semibold">Type</label>
                <select name="type" class="form-select" required>
                    <option value="renouvellement"
                        {{ old('type', $periode->type) == 'renouvellement' ? 'selected' : '' }}>
                        Renouvellement
                    </option>
                    <option value="changement"
                        {{ old('type', $periode->type) == 'changement' ? 'selected' : '' }}>
                        Changement
                    </option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Libellé</label>
                <input type="text"
                       name="libelle"
                       class="form-control"
                       value="{{ old('libelle', $periode->libelle) }}"
                       required>
            </div>

            <div class="row mb-3">
                <div class="col">
                    <label class="form-label fw-semibold">Date début</label>
                    <input type="date"
                           name="date_debut"
                           class="form-control"
                           value="{{ old('date_debut', $periode->date_debut->format('Y-m-d')) }}"
                           required>
                </div>

                <div class="col">
                    <label class="form-label fw-semibold">Date fin</label>
                    <input type="date"
                           name="date_fin"
                           class="form-control"
                           value="{{ old('date_fin', $periode->date_fin->format('Y-m-d')) }}"
                           required>
                </div>
            </div>

            <div class="mb-4">
               <div class="form-check form-switch">
    <input type="hidden" name="active" value="0">

    <input class="form-check-input" type="checkbox" name="active" value="1"
           {{ old('active', $periode->active) ? 'checked' : '' }}>

    <label class="form-check-label">Période active</label>
</div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i>Enregistrer
                </button>

                <a href="{{ route('admin.periodes.index') }}"
                   class="btn btn-outline-secondary">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection