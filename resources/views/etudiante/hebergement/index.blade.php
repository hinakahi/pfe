@extends('layouts.app')

@section('title', 'Mon Hébergement')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Mon Hébergement</h4>
        <small class="text-muted">Renouvellement de chambre</small>
    </div>
</div>

{{-- Messages --}}
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

{{-- Période de renouvellement --}}
@if($periodeRenouvellement)
    <div class="alert alert-info">
        <i class="bi bi-info-circle me-1"></i>
        La période de renouvellement est ouverte jusqu'au
        <strong>{{ \Carbon\Carbon::parse($periodeRenouvellement->date_fin)->format('d/m/Y') }}</strong>
    </div>
@else
    <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle me-1"></i>
        Aucune période de renouvellement n'est active pour le moment.
    </div>
@endif

{{-- Formulaire de renouvellement --}}
@if($periodeRenouvellement)
<div class="card mb-4">
    <div class="card-body">
        <h6 class="fw-bold mb-3">Demande de renouvellement</h6>
        <form action="{{ route('etudiante.hebergement.renouveller') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label">Numéro de chambre</label>
                <div class="mb-3">
    <label class="form-label">Numéro de chambre</label>
    @if($maChambre)
        <input type="hidden" name="chambre_id" value="{{ $maChambre->id }}">
        <input type="text" class="form-control"
               value="Chambre {{ $maChambre->numero }} — Bloc {{ $maChambre->bloc }} — Étage {{ $maChambre->etage }}"
               disabled>
    @else
        <div class="alert alert-warning">Aucune chambre assignée à votre compte.</div>
    @endif
</div>
                @error('chambre_id')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Justificatif de scolarité</label>
                <input type="file" name="justificatif_scolarite" class="form-control" accept=".pdf,.jpg,.png" required>
                @error('justificatif_scolarite')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Justificatif de paiement</label>
                <input type="file" name="justificatif_paiement" class="form-control" accept=".pdf,.jpg,.png" required>
                @error('justificatif_paiement')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-send me-1"></i> Envoyer la demande
            </button>
        </form>
    </div>
</div>
@endif

{{-- Historique des demandes --}}
<div class="card">
    <div class="card-body">
        <h6 class="fw-bold mb-3">Mes demandes de renouvellement</h6>
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>Chambre</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                @forelse($demandesRenouvellement as $demande)
                <tr>
                    <td>{{ $demande->created_at->format('d/m/Y') }}</td>
                    <td>Chambre {{ $demande->chambre->numero ?? '-' }}</td>
                    <td>
                        @if($demande->statut === 'en_attente')
                            <span class="badge bg-warning text-dark">En attente</span>
                        @elseif($demande->statut === 'validee')
                            <span class="badge bg-success">Validée</span>
                        @elseif($demande->statut === 'refusee')
                            <span class="badge bg-danger">Refusée</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center text-muted py-3">Aucune demande effectuée.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection