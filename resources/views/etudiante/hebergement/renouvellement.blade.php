@extends('layouts.app')
@section('page-title', 'Renouvellement de chambre')
@section('content')

{{-- Messages flash --}}

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Titre --}}
<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('etudiante.hebergement.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i>
    </a>
    
    
</div>

{{-- Ma chambre --}}
@if($maChambre)
<div class="p-3 rounded-3 text-white mb-4" style="background: linear-gradient(135deg, #1a3c5e, #2d6a9f);">
    <div class="d-flex align-items-center gap-3">
        <i class="bi bi-door-open" style="font-size:2rem;"></i>
        <div>
            <div class="small opacity-75">Ma chambre</div>
            <div class="fw-bold fs-5">
                Chambre {{ $maChambre->numero }} — Bloc {{ $maChambre->bloc }} — Étage {{ $maChambre->etage }}
            </div>
        </div>
    </div>
</div>
@endif

@php
    $derniereDemande = $demandesRenouvellement->first();
    $demandeEnCours = $demandesRenouvellement
    ->whereIn('statut', ['en_attente', 'validee'])
    ->filter(fn($d) => $periodeRenouvellement && 
        $d->created_at >= $periodeRenouvellement->date_debut && 
        $d->created_at <= $periodeRenouvellement->date_fin)
    ->first();
@endphp

{{-- Carte d'action --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">

        @if(!$periodeRenouvellement)
            <div class="alert alert-warning d-flex align-items-center gap-2 mb-0">
                <i class="bi bi-lock-fill fs-5"></i>
                <div>
                    <strong>Période fermée</strong> — Les demandes de renouvellement ne sont pas disponibles pour le moment.
                </div>
            </div>

        @elseif(!$maChambre)
            <div class="alert alert-warning py-2 small mb-0">
                <i class="bi bi-exclamation-triangle me-1"></i> Aucune chambre assignée.
            </div>

        @elseif($demandeEnCours)
            <div class="alert alert-info d-flex align-items-center gap-2 mb-0 py-2">
                <i class="bi bi-hourglass-split me-1"></i>
                <span>Vous avez déjà une demande
                    @if($demandeEnCours->statut === 'en_attente')
                        <strong>en attente</strong> de traitement.
                    @else
                        <strong>validée</strong>.
                    @endif
                </span>
            </div>

        @else
            <div class="alert alert-info d-flex align-items-center gap-2 mb-3 py-2">
                <i class="bi bi-info-circle me-1"></i>
                <span>Période ouverte jusqu'au <strong>{{ \Carbon\Carbon::parse($periodeRenouvellement->date_fin)->format('d/m/Y') }}</strong></span>
            </div>
            <button class="btn text-white fw-semibold" style="background:#28a745;"
                    data-bs-toggle="modal" data-bs-target="#modalRenouvellement">
                <i class="bi bi-send me-1"></i> Faire une demande
            </button>
        @endif

    </div>
</div>

{{-- Détail dernière demande --}}
@if($derniereDemande)
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <h6 class="fw-bold mb-3">
            <i class="bi bi-file-earmark-text me-2 text-secondary"></i>Ma dernière demande
        </h6>

        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
            <div>
                <div class="small text-muted">Date de la demande</div>
                <div class="fw-semibold">{{ $derniereDemande->created_at->format('d/m/Y') }}</div>
            </div>
            <div>
                <div class="small text-muted">Chambre</div>
                <div class="fw-semibold">{{ $derniereDemande->chambre->numero ?? '-' }}</div>
            </div>
            <div>
                <div class="small text-muted">Statut</div>
                @if($derniereDemande->statut === 'en_attente')
                    <span class="badge bg-warning text-dark">En attente</span>
                @elseif($derniereDemande->statut === 'validee')
                    <span class="badge bg-success">Validée</span>
                @else
                    <span class="badge bg-danger">Refusée</span>
                @endif
            </div>
        </div>

        @if($derniereDemande->statut === 'refusee' && $derniereDemande->motif_refus)
            <div class="alert alert-danger py-2 small mb-3">
                <i class="bi bi-exclamation-circle me-1"></i>
                <strong>Motif du refus :</strong> {{ $derniereDemande->motif_refus }}
            </div>
        @endif
         {{-- Documents générés après validation --}}
@if($derniereDemande->statut === 'validee' && ($derniereDemande->decision_pdf || $derniereDemande->prise_en_charge_pdf))
<div class="alert alert-success py-2 mb-3">
    <div class="fw-semibold small mb-2"><i class="bi bi-check-circle me-1"></i> Documents disponibles</div>
    <div class="row g-2">
        @if($derniereDemande->decision_pdf)
        <div class="col-md-6">
            <a href="{{ asset('storage/' . $derniereDemande->decision_pdf) }}" target="_blank"
               class="btn btn-sm btn-outline-success w-100">
                <i class="bi bi-file-earmark-pdf me-1"></i> Décision de réadmission
            </a>
        </div>
        @endif
        @if($derniereDemande->prise_en_charge_pdf)
        <div class="col-md-6">
            <a href="{{ asset('storage/' . $derniereDemande->prise_en_charge_pdf) }}" target="_blank"
               class="btn btn-sm btn-outline-success w-100">
                <i class="bi bi-file-earmark-pdf me-1"></i> Prise en charge
            </a>
        </div>
        @endif
    </div>
</div>
@endif
        {{-- Fichiers envoyés --}}
        <div class="row g-2 mb-3">
            <div class="col-md-6">
                <div class="border rounded-2 p-2 d-flex align-items-center justify-content-between">
                    <span class="small"><i class="bi bi-file-earmark-pdf me-1 text-danger"></i> Justificatif de scolarité</span>
                    @if($derniereDemande->justificatif_scolarite)
                        <a href="{{ asset('storage/' . $derniereDemande->justificatif_scolarite) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-eye"></i>
                        </a>
                    @else
                        <span class="text-muted small">-</span>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="border rounded-2 p-2 d-flex align-items-center justify-content-between">
                    <span class="small"><i class="bi bi-file-earmark-pdf me-1 text-danger"></i> Justificatif de paiement</span>
                    @if($derniereDemande->justificatif_paiement)
                        <a href="{{ asset('storage/' . $derniereDemande->justificatif_paiement) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-eye"></i>
                        </a>
                    @else
                        <span class="text-muted small">-</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Bouton modifier si refusée --}}
        @if($derniereDemande->statut === 'refusee')
            <button class="btn btn-sm text-white" style="background:#1a3c5e;"
                    data-bs-toggle="modal" data-bs-target="#modalModifier{{ $derniereDemande->id }}">
                <i class="bi bi-pencil me-1"></i> Modifier et renvoyer
            </button>

            {{-- Modal modification --}}
            <div class="modal fade" id="modalModifier{{ $derniereDemande->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content border-0 shadow">
                        <div class="modal-header" style="background: linear-gradient(135deg, #1a3c5e, #2d6a9f);">
                            <h5 class="modal-title text-white">
                                <i class="bi bi-pencil me-2"></i>Modifier ma demande
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ route('etudiante.hebergement.renouvellement.modifier', $derniereDemande->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <p class="text-muted small">Laissez vide pour conserver le fichier déjà envoyé.</p>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Justificatif de scolarité</label>
                                    <input type="file" name="justificatif_scolarite" class="form-control" accept=".pdf,.jpg,.png">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Justificatif de paiement</label>
                                    <input type="file" name="justificatif_paiement" class="form-control" accept=".pdf,.jpg,.png">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Annuler</button>
                                <button type="submit" class="btn btn-sm text-white" style="background:#1a3c5e;"
        onclick="this.disabled=true; this.innerHTML='<i class=\'bi bi-hourglass-split me-1\'></i> Envoi...'; this.form.submit();">
    <i class="bi bi-send me-1"></i> Renvoyer la demande
</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endif

{{-- Historique complet --}}
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <h6 class="fw-bold mb-3">
            <i class="bi bi-clock-history me-2 text-secondary"></i>Historique des demandes
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
                    <td colspan="4" class="text-center text-muted py-3">Aucune demande.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Modal nouvelle demande --}}
@if($periodeRenouvellement && $maChambre && !$demandeEnCours)
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