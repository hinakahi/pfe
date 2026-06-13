@extends('layouts.app')
@section('title', 'Importer des chambres')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Importer des chambres</h4>
        <small class="text-muted">Importation via fichier Excel ou CSV</small>
    </div>
    <a href="{{ route('hebergement.chambres.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Retour
    </a>
</div>

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-body">
        <form action="{{ route('hebergement.chambres.import.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label class="form-label fw-bold">Fichier Excel (.xlsx) ou CSV</label>
                <input type="file" name="fichier" class="form-control" accept=".xlsx,.csv,.xls" required>
                @error('fichier')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="alert alert-info">
                <i class="bi bi-info-circle me-1"></i>
                Le fichier doit contenir les colonnes suivantes dans l'ordre :
                <strong>numero, type, bloc, etage, capacite, etudiante_1, etudiante_2</strong>
                <br>
                <small class="text-muted">
                    — <code>type</code> : <code>individuelle</code> ou <code>double</code><br>
                    — <code>etudiante_1</code> et <code>etudiante_2</code> : matricules<br>
                    — <code>etudiante_2</code> uniquement pour les chambres double
                </small>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-upload me-1"></i> Importer
                </button>
                <a href="{{ route('hebergement.chambres.index') }}" class="btn btn-outline-secondary">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>


@endsection