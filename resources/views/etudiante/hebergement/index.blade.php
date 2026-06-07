@extends('layouts.app')
@section('page-title', 'Mon Hébergement')
@section('content')

{{-- Messages flash --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Titre --}}
<div class="mb-4">
    <h4 class="fw-bold mb-1">Mon Hébergement</h4>
    <p class="text-muted mb-0">Gérer votre chambre et vos demandes.</p>
</div>

{{-- Ma chambre --}}
<div class="p-4 rounded-3 text-white mb-4" style="background: linear-gradient(135deg, #1a3c5e, #2d6a9f);">
    <div class="d-flex align-items-center gap-3">
        <i class="bi bi-door-open" style="font-size:2.5rem;"></i>
        <div>
            <div class="small opacity-75">Ma chambre</div>
            @if($maChambre)
                <div class="fw-bold fs-4">Chambre {{ $maChambre->numero }} — Bloc {{ $maChambre->bloc }} — Étage {{ $maChambre->etage }}</div>
            @else
                <div class="fw-bold fs-4 opacity-75">Non assignée</div>
            @endif
        </div>
    </div>
</div>

{{-- 2 cartes actions --}}
<div class="row g-3 mb-4">

    {{-- Renouvellement --}}
    <div class="col-md-6">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white"
                         style="width:38px;height:38px;background:#28a745;">
                        <i class="bi bi-arrow-repeat"></i>
                    </div>
                    <h6 class="fw-bold mb-0">Renouvellement de chambre</h6>
                </div>
                <p class="text-muted small mb-3">Demandez le renouvellement de votre chambre pour l'année prochaine.</p>

                @if($periodeRenouvellement)
                    <div class="rounded-2 px-3 py-2 small mb-3" style="background:#e8f4fd; color:#1a3c5e;">
                        <i class="bi bi-info-circle me-1"></i>
                        Période ouverte jusqu'au
                        <strong>{{ \Carbon\Carbon::parse($periodeRenouvellement->date_fin)->format('d/m/Y') }}</strong>
                    </div>
                    @if($maChambre)
                        <button class="btn btn-sm w-100 text-white fw-semibold"
                                style="background:#28a745;"
                                data-bs-toggle="modal" data-bs-target="#modalRenouvellement">
                            <i class="bi bi-send me-1"></i> Faire une demande
                        </button>
                    @else
                        <div class="alert alert-warning py-2 small mb-0">Aucune chambre assignée.</div>
                    @endif
                @else
                    <div class="alert alert-warning py-2 small mb-0">
                        <i class="bi bi-exclamation-triangle me-1"></i> Aucune période active.
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Changement --}}
    <div class="col-md-6">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white"
                         style="width:38px;height:38px;background:#fd7e14;">
                        <i class="bi bi-shuffle"></i>
                    </div>
                    <h6 class="fw-bold mb-0">Changement de chambre</h6>
                </div>
                <p class="text-muted small mb-3">Demandez à changer de chambre en choisissant parmi les disponibles.</p>

                @if($maChambre)
                    <a href="{{ route('etudiante.changement') }}"
                       class="btn btn-sm w-100 text-white fw-semibold"
                       style="background:#fd7e14;">
                        <i class="bi bi-shuffle me-1"></i> Voir les chambres disponibles
                    </a>
                @else
                    <div class="alert alert-warning py-2 small mb-0">Aucune chambre assignée.</div>
                @endif
            </div>
        </div>
    </div>

</div>

{{-- Historique --}}
<div class="row g-3">

    {{-- Renouvellements --}}
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="fw-bold mb-3">
                    <i class="bi bi-clock-history me-2 text-secondary"></i>Mes renouvellements
                </h6>
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Chambre</th>
                            <th>Statut</th>
                            <th>Motif refus</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($demandesRenouvellement as $d)
                        <tr>
                            <td><small>{{ $d->created_at->format('d/m/Y') }}</small></td>
                            <td>{{ $d->chambre->numero ?? '-' }}</td>
                            <td>
                                @if($d->statut === 'en_attente')
                                    <span class="badge bg-warning text-dark">En attente</span>
                                @elseif($d->statut === 'validee')
                                    <span class="badge bg-success">Validée</span>
                                @else
                                    <span class="badge bg-danger">Refusée</span>
                                @endif
                            </td>
                            <td><small class="text-muted">{{ $d->motif_refus ?? '-' }}</small></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-3">Aucune demande.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Changements --}}
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="fw-bold mb-3">
                    <i class="bi bi-shuffle me-2 text-secondary"></i>Mes changements
                </h6>
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Chambre demandée</th>
                            <th>Statut</th>
                            <th>Motif refus</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($demandesChangement as $d)
                        <tr>
                            <td><small>{{ $d->created_at->format('d/m/Y') }}</small></td>
                            <td>{{ $d->chambreDemandee->numero ?? '-' }}</td>
                            <td>
                                @if($d->statut === 'en_attente')
                                    <span class="badge bg-warning text-dark">En attente</span>
                                @elseif($d->statut === 'acceptee')
                                    <span class="badge bg-success">Acceptée</span>
                                @else
                                    <span class="badge bg-danger">Refusée</span>
                                @endif
                            </td>
                            <td><small class="text-muted">{{ $d->motif_refus ?? '-' }}</small></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-3">Aucune demande.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

{{-- Modal Renouvellement --}}
@if($periodeRenouvellement && $maChambre)
<div class="modal fade" id="modalRenouvellement" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header" style="background: linear-gradient(135deg, #1a3c5e, #2d6a9f);">
                <h5 class="modal-title text-white">
                    <i class="bi bi-arrow-repeat me-2"></i>Demande de renouvellement
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('etudiante.hebergement.renouveller') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="chambre_id" value="{{ $maChambre->id }}">
                <div class="modal-body">
                    <div class="rounded-2 px-3 py-2 small mb-3" style="background:#e8f4fd; color:#1a3c5e;">
                        <i class="bi bi-door-open me-1"></i>
                        Chambre <strong>{{ $maChambre->numero }}</strong> — Bloc {{ $maChambre->bloc }} — Étage {{ $maChambre->etage }}
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Justificatif de scolarité</label>
                        <input type="file" name="justificatif_scolarite" class="form-control" accept=".pdf,.jpg,.png" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Justificatif de paiement</label>
                        <input type="file" name="justificatif_paiement" class="form-control" accept=".pdf,.jpg,.png" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-sm text-white" style="background:#28a745;">
                        <i class="bi bi-send me-1"></i> Envoyer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@endsection