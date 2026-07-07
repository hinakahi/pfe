@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width: 750px;">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('etudiante.maintenance.index') }}">Maintenance</a>
            </li>
            <li class="breadcrumb-item active">Demande #{{ $maintenance->id }}</li>
        </ol>
    </nav>

    {{-- Card principale --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">Détails de la demande</h5>
            <div class="d-flex gap-2">
                @php
                    $statutClass = match($maintenance->statut) {
                        'en_attente' => 'warning text-dark',
                        'en_cours'   => 'info text-dark',
                        'terminee'   => 'success',
                        default      => 'secondary',
                    };
                    $statutLabel = match($maintenance->statut) {
                        'en_attente' => 'En attente',
                        'en_cours'   => 'En cours',
                        'terminee'   => 'Terminée',
                        default      => $maintenance->statut,
                    };
                @endphp
                @if($maintenance->urgence === 'urgente')
                    <span class="badge bg-danger fs-6">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i>Urgente
                    </span>
                @else
                    <span class="badge bg-secondary fs-6">Normale</span>
                @endif
                <span class="badge bg-{{ $statutClass }} fs-6">{{ $statutLabel }}</span>
            </div>
        </div>

        <div class="card-body p-4">
            <div class="row g-4">

                <div class="col-md-6">
                    <p class="text-muted small mb-1 fw-semibold text-uppercase">Chambre</p>
                    <p class="mb-0 fs-5">{{ $maintenance->chambre->numero ?? '—' }}</p>
                </div>

                <div class="col-md-6">
                    <p class="text-muted small mb-1 fw-semibold text-uppercase">Type</p>
                    <p class="mb-0 fs-5">{{ ucfirst($maintenance->type) }}</p>
                </div>

                <div class="col-12">
                    <p class="text-muted small mb-1 fw-semibold text-uppercase">Description</p>
                    <p class="mb-0">{{ $maintenance->description }}</p>
                </div>

                <div class="col-md-6">
                    <p class="text-muted small mb-1 fw-semibold text-uppercase">Date de signalement</p>
                    <p class="mb-0">{{ $maintenance->created_at->format('d/m/Y à H:i') }}</p>
                </div>

                @if($maintenance->date_resolution)
                <div class="col-md-6">
                    <p class="text-muted small mb-1 fw-semibold text-uppercase">Date de résolution</p>
                    <p class="mb-0">{{ $maintenance->date_resolution->format('d/m/Y à H:i') }}</p>
                </div>
                @endif

                @if($maintenance->technicien)
                <div class="col-md-6">
                    <p class="text-muted small mb-1 fw-semibold text-uppercase">Technicien assigné</p>
                    <p class="mb-0">
                        <i class="bi bi-person-gear me-1 text-primary"></i>
                        {{ $maintenance->technicien->name }}
                    </p>
                </div>
                @endif

            </div>

            {{-- Matériels si présents --}}
            @if($maintenance->materiels->isNotEmpty())
            <hr class="my-4">
            <h6 class="fw-bold mb-3">Matériels utilisés</h6>
            <div class="table-responsive">
                <table class="table table-sm table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Désignation</th>
                            <th class="text-center">Quantité</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($maintenance->materiels as $materiel)
                        <tr>
                            <td>{{ $materiel->stock->designation ?? '' }}</td>
                            <td class="text-center">{{ $materiel->quantite ?? '—' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

        <div class="card-footer bg-white border-top py-3 d-flex justify-content-between">
            <a href="{{ route('etudiante.maintenance.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Retour
            </a>

            @if($maintenance->statut === 'en_attente')
            <div class="d-flex gap-2">
                <a href="{{ route('etudiante.maintenance.edit', $maintenance) }}"
                   class="btn btn-outline-primary">
                    <i class="bi bi-pencil me-1"></i>Modifier
                </a>
                <form action="{{ route('etudiante.maintenance.destroy', $maintenance) }}"
                      method="POST"
                      onsubmit="return confirm('Annuler cette demande ?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger">
                        <i class="bi bi-x-circle me-1"></i>Annuler la demande
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>

</div>
@endsection