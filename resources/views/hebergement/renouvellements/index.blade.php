@extends('layouts.app')
@section('page-title', 'Demandes de Renouvellement')

@section('content')

<div class="mb-4">
    <h4 class="fw-bold mb-1">Demandes de renouvellement</h4>
    <p class="text-muted mb-0">Vérifier les justificatifs et traiter les demandes.</p>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="p-4 rounded-3 text-white" style="background: linear-gradient(135deg, #fd7e14, #ffc107);">
            <div style="font-size:2rem; font-weight:700;">{{ $enAttente->count() }}</div>
            <div><i class="bi bi-hourglass-split me-1"></i> En attente de traitement</div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="p-4 rounded-3 text-white" style="background: linear-gradient(135deg, #28a745, #20c997);">
            <div style="font-size:2rem; font-weight:700;">{{ $traitees->count() }}</div>
            <div><i class="bi bi-check-circle me-1"></i> Traitées récemment</div>
        </div>
    </div>
</div>

{{-- En attente --}}
<div class="card mb-4">
    <div class="card-body">
        <h6 class="fw-bold mb-3"><i class="bi bi-hourglass-split me-2 text-warning"></i>En attente</h6>
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Étudiante</th>
                    <th>Chambre</th>
                    <th>Justif. Scolarité</th>
                    <th>Justif. Paiement</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($enAttente as $demande)
                <tr>
                    <td>
                        <strong>{{ $demande->etudiante->name }}</strong><br>
                        <small class="text-muted">{{ $demande->etudiante->matricule }}</small>
                    </td>
                    <td>{{ $demande->chambre->numero ?? '-' }}</td>
                    <td>
                        @if($demande->justificatif_scolarite)
                            <a href="{{ asset('storage/'.$demande->justificatif_scolarite) }}"
                               target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-file-earmark me-1"></i>Voir
                            </a>
                        @else
                            <span class="text-muted">Aucun</span>
                        @endif
                    </td>
                    <td>
                        @if($demande->justificatif_paiement)
                            <a href="{{ asset('storage/'.$demande->justificatif_paiement) }}"
                               target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-file-earmark me-1"></i>Voir
                            </a>
                        @else
                            <span class="text-muted">Aucun</span>
                        @endif
                    </td>
                    <td><small>{{ $demande->created_at->format('d/m/Y') }}</small></td>
                    <td>
                        {{-- Valider --}}
                        <form method="POST"
                              action="{{ route('hebergement.renouvellements.valider', $demande) }}"
                              class="d-inline">
                            @csrf
                            <button class="btn btn-sm btn-success"
                                    onclick="return confirm('Valider ce renouvellement ?')">
                                <i class="bi bi-check-lg"></i> Valider
                            </button>
                        </form>

                        {{-- Refuser --}}
                        <button class="btn btn-sm btn-danger"
                                data-bs-toggle="modal"
                                data-bs-target="#refusModal{{ $demande->id }}">
                            <i class="bi bi-x-lg"></i> Refuser
                        </button>

                        {{-- Modal refus --}}
                        <div class="modal fade" id="refusModal{{ $demande->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Motif du refus</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="POST"
                                          action="{{ route('hebergement.renouvellements.refuser', $demande) }}">
                                        @csrf
                                        <div class="modal-body">
                                            <textarea name="motif_refus" class="form-control" rows="4"
                                                      placeholder="Expliquez la raison du refus..." required></textarea>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                            <button type="submit" class="btn btn-danger">Confirmer le refus</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">Aucune demande en attente.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Traitées --}}
<div class="card">
    <div class="card-body">
        <h6 class="fw-bold mb-3"><i class="bi bi-clock-history me-2 text-secondary"></i>Traitées récemment</h6>
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Étudiante</th>
                    <th>Chambre</th>
                    <th>Statut</th>
                    <th>Motif refus</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($traitees as $demande)
                <tr>
                    <td>
                        <strong>{{ $demande->etudiante->name }}</strong><br>
                        <small class="text-muted">{{ $demande->etudiante->matricule }}</small>
                    </td>
                    <td>{{ $demande->chambre->numero ?? '-' }}</td>
                    <td>
                        @if($demande->statut === 'validee')
                            <span class="badge bg-success">Validée</span>
                        @else
                            <span class="badge bg-danger">Refusée</span>
                        @endif
                    </td>
                    <td><small>{{ $demande->motif_refus ?? '-' }}</small></td>
                    <td><small>{{ $demande->updated_at->format('d/m/Y') }}</small></td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">Aucune demande traitée.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection