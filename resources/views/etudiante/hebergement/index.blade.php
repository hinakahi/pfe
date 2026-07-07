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

{{-- 2 cartes cliquables --}}
<div class="row g-3 mb-4">

    {{-- Renouvellement --}}
    <div class="col-md-6">
        <a href="{{ route('etudiante.hebergement.renouvellement') }}" class="text-decoration-none">
            <div class="card h-100 border-0 shadow-sm hover-card">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white"
                             style="width:38px;height:38px;background:#28a745;">
                            <i class="bi bi-arrow-repeat"></i>
                        </div>
                        <h6 class="fw-bold mb-0">Renouvellement de chambre</h6>

                        <i class="bi bi-chevron-right ms-auto text-muted"></i>
                    </div>
                    <p class="text-muted small mb-3">Demandez le renouvellement de votre chambre pour l'année prochaine.</p>

                    @if($periodeRenouvellement)
                        <div class="rounded-2 px-3 py-2 small mb-0 periode-badge">
                            <i class="bi bi-info-circle me-1"></i>
                            Période ouverte jusqu'au
                            <strong>{{ \Carbon\Carbon::parse($periodeRenouvellement->date_fin)->format('d/m/Y') }}</strong>
                        </div>
                    @else
                        <div class="alert alert-warning py-2 small mb-0">
                            <i class="bi bi-exclamation-triangle me-1"></i> Aucune période active.
                        </div>
                    @endif
                </div>
            </div>
        </a>
    </div>

    {{-- Changement --}}
    <div class="col-md-6">
        <a href="{{ route('etudiante.changement') }}" class="text-decoration-none">
            <div class="card h-100 border-0 shadow-sm hover-card">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white"
                             style="width:38px;height:38px;background:#fd7e14;">
                            <i class="bi bi-shuffle"></i>
                        </div>
                        <h6 class="fw-bold mb-0 ">Changement de chambre</h6>
                        <i class="bi bi-chevron-right ms-auto text-muted"></i>
                    </div>
                    <p class="text-muted small mb-3">Demandez à changer de chambre en choisissant parmi les disponibles.</p>

                    @if($periodeChangement)
                        <div class="rounded-2 px-3 py-2 small mb-0 periode-badge">
                            <i class="bi bi-info-circle me-1"></i>
                            Période ouverte jusqu'au
                            <strong>{{ \Carbon\Carbon::parse($periodeChangement->date_fin)->format('d/m/Y') }}</strong>
                        </div>
                    @else
                        <div class="alert alert-warning py-2 small mb-0">
                            <i class="bi bi-exclamation-triangle me-1"></i> Aucune période active.
                        </div>
                    @endif
                </div>
            </div>
        </a>
    </div>

</div>

{{-- Dernières demandes --}}
<div class="row g-3">

    {{-- Dernier renouvellement --}}
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="fw-bold mb-3">
                    <i class="bi bi-clock-history me-2 text-secondary"></i>Dernière demande de renouvellement
                </h6>
                @if($demandesRenouvellement->isNotEmpty())
                    @php $d = $demandesRenouvellement->first(); @endphp
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small text-muted">{{ $d->created_at->format('d/m/Y') }}</div>
                            <div>Chambre {{ $d->chambre->numero ?? '-' }}</div>
                        </div>
                        <div class="text-end">
                            @if($d->statut === 'en_attente')
                                <span class="badge bg-warning text-dark">En attente</span>
                            @elseif($d->statut === 'validee')
                                <span class="badge bg-success">Validée</span>
                            @else
                                <span class="badge bg-danger">Refusée</span>
                                @if($d->motif_refus)
                                    <div class="small text-muted mt-1">{{ $d->motif_refus }}</div>
                                @endif
                            @endif
                        </div>
                    </div>
                @else
                    <p class="text-muted text-center mb-0 py-2">Aucune demande.</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Dernier changement --}}
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="fw-bold mb-3">
                    <i class="bi bi-shuffle me-2 text-secondary"></i>Dernière demande de changement
                </h6>
                @if($demandesChangement->isNotEmpty())
                    @php $d = $demandesChangement->first(); @endphp
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small text-muted">{{ $d->created_at->format('d/m/Y') }}</div>
                            <div>Chambre {{ $d->chambreDemandee->numero ?? '-' }}</div>
                        </div>
                        <div class="text-end">
                            @if($d->statut === 'en_attente')
                                <span class="badge bg-warning text-dark">En attente</span>
                            @elseif($d->statut === 'acceptee')
                                <span class="badge bg-success">Acceptée</span>
                            @else
                                <span class="badge bg-danger">Refusée</span>
                                @if($d->motif_refus)
                                    <div class="small text-muted mt-1">{{ $d->motif_refus }}</div>
                                @endif
                            @endif
                        </div>
                    </div>
                @else
                    <p class="text-muted text-center mb-0 py-2">Aucune demande.</p>
                @endif
            </div>
        </div>
    </div>

</div>

<style>
    .hover-card { transition: transform 0.15s ease, box-shadow 0.15s ease; }
    .hover-card:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0,0,0,0.1) !important; }
    [data-theme="dark"] .text-muted {
            color: var(--text-muted) !important;
        }
        [data-theme="dark"] .card,
        [data-theme="dark"] .modal-content,
        [data-theme="dark"] .modal-header,
        [data-theme="dark"] .modal-footer {
            background-color: var(--bg-card) !important;
            color: var(--text-main);
            border-color: #444 !important;
        }
        [data-theme="dark"] .bg-white,
        [data-theme="dark"] .bg-light {
            background-color: #2d3139 !important;
            color: var(--text-main) !important;
            border-color: #444 !important;
        }
        [data-theme="dark"] .alert-info,
        [data-theme="dark"] .alert-light {
            background-color: #1f3a4d !important;
            color: var(--text-main) !important;
            border-color: #2d6a9f !important;
        }
        [data-theme="dark"] .btn-close {
            filter: invert(1);
        }
        [data-theme="dark"] [style*="background"] h2,
        [data-theme="dark"] [style*="background"] h5,
        [data-theme="dark"] [style*="background"] p {
            color: inherit;
        }
        .periode-badge {
        background: #e8f4fd;
        color: #1a3c5e;
    }
    [data-theme="dark"] .periode-badge {
        background: #1f3a4d;
        color: var(--text-main);
    }
</style>


@endsection