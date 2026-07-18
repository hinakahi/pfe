@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width: 800px;">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('etudiante.maintenance.index') }}">Maintenance</a>
            </li>
            <li class="breadcrumb-item active">Demande #{{ $maintenance->id }}</li>
        </ol>
    </nav>

    @php
        $borderColor = match($maintenance->statut) {
            'en_attente' => '#ffc107',
            'en_cours'   => '#0d6efd',
            'terminee'   => '#198754',
            default      => '#dee2e6',
        };
        $statutClass = match($maintenance->statut) {
            'en_attente' => 'warning text-dark',
            'en_cours'   => 'primary',
            'terminee'   => 'success',
            default      => 'secondary',
        };
        $statutLabel = match($maintenance->statut) {
            'en_attente' => 'En attente',
            'en_cours'   => 'En cours',
            'terminee'   => 'Terminée',
            default      => $maintenance->statut,
        };
        $typeIcons = [
            'electricite' => 'bi-lightning-charge',
            'plomberie'   => 'bi-droplet',
            'menuiserie'  => 'bi-hammer',
            'autre'       => 'bi-tools',
        ];
    @endphp

    {{-- Card principale --}}
    <div class="card shadow-sm" style="border-left: 5px solid {{ $borderColor }};">
        <div class="card-body p-4">

            {{-- En-tête : ID + badges --}}
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <span class="text-muted fw-bold">#{{ $maintenance->id }}</span>
                    <span class="badge bg-{{ $statutClass }}">{{ $statutLabel }}</span>

                    @if($maintenance->urgence === 'urgente')
                        <span class="badge bg-danger">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i>Urgente
                        </span>
                    @else
                        <span class="badge bg-light text-dark border">Normale</span>
                    @endif

                    <span class="badge bg-light text-dark border">
                        <i class="bi {{ $typeIcons[$maintenance->type] ?? 'bi-tools' }} me-1"></i>
                        {{ ucfirst($maintenance->type) }}
                    </span>
                </div>
            </div>

            {{-- Localisation + date --}}
            <div class="d-flex flex-wrap gap-4 text-muted mb-3 pb-3 border-bottom" style="font-size:.92rem;">
                @if($maintenance->chambre)
                    <span><i class="bi bi-door-closed me-1"></i>Chambre {{ $maintenance->chambre->numero }}</span>
                    <span><i class="bi bi-building me-1"></i>Bloc {{ $maintenance->chambre->bloc }}</span>
                    <span><i class="bi bi-layers me-1"></i>Étage {{ $maintenance->chambre->etage }}</span>
                @elseif($maintenance->lieu_commun)
                    <span><i class="bi bi-geo-alt me-1"></i>{{ $maintenance->lieu_commun }}</span>
                @endif
                <span><i class="bi bi-calendar me-1"></i>{{ $maintenance->created_at->format('d/m/Y à H:i') }}</span>
            </div>

            {{-- Description --}}
            <div class="mb-3">
                <div class="text-muted mb-1" style="font-size:.75rem;font-weight:700;text-transform:uppercase;">
                    Description
                </div>
                <p class="mb-0">{{ $maintenance->description }}</p>
            </div>

            {{-- Photo si présente --}}
            @if($maintenance->photo)
            <div class="mb-3">
                <div class="text-muted mb-2" style="font-size:.75rem;font-weight:700;text-transform:uppercase;">
                    Photo
                </div>
                <a href="{{ asset('storage/' . $maintenance->photo) }}" target="_blank">
                    <img src="{{ asset('storage/' . $maintenance->photo) }}" alt="Photo de la demande"
                         style="max-height:220px;border-radius:.5rem;" class="border">
                </a>
            </div>
            @endif

            {{-- Technicien / résolution --}}
            @if($maintenance->technicien || $maintenance->date_resolution)
            <div class="alert {{ $maintenance->statut === 'terminee' ? 'alert-success' : 'alert-primary' }} py-2 mb-3" style="font-size:.88rem;">
                @if($maintenance->technicien)
                    <div>
                        <i class="bi bi-person-gear me-1"></i>
                        <strong>Technicien assigné :</strong> {{ $maintenance->technicien->name }}
                    </div>
                @endif
                @if($maintenance->date_resolution)
                    <div class="{{ $maintenance->technicien ? 'mt-1' : '' }}">
                        <i class="bi bi-check-circle me-1"></i>
                        <strong>Résolue le :</strong> {{ $maintenance->date_resolution->format('d/m/Y à H:i') }}
                    </div>
                @endif
            </div>
            @endif

            {{-- Matériels si présents --}}
            @if($maintenance->materiels->isNotEmpty())
            <div class="mb-1">
                <div class="text-muted mb-2" style="font-size:.75rem;font-weight:700;text-transform:uppercase;">
                    Matériel utilisé
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Désignation</th>
                                <th class="text-center" style="width:120px;">Quantité</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($maintenance->materiels as $materiel)
                            <tr>
                                <td><i class="bi bi-box me-1 text-muted"></i>{{ $materiel->stock->designation ?? 'Matériel supprimé' }}</td>
                                <td class="text-center">{{ $materiel->quantite ?? '—' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
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