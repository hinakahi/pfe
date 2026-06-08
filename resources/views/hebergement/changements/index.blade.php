@extends('layouts.app')
@section('page-title', 'Demandes de Changement')

@section('content')

<div class="mb-4">
    <h4 class="fw-bold mb-1">Demandes de changement de chambre</h4>
    <p class="text-muted mb-0">Traiter les demandes de changement soumises par les étudiantes.</p>
</div>

{{-- Stats cliquables --}}
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <a href="#section-attente" style="text-decoration:none;">
            <div class="p-4 rounded-3 text-white" style="background: linear-gradient(135deg, #fd7e14, #ffc107); cursor:pointer; transition: opacity .2s;" onmouseover="this.style.opacity='.85'" onmouseout="this.style.opacity='1'">
                <div style="font-size:2rem; font-weight:700;">{{ $enAttente->count() }}</div>
                <div><i class="bi bi-hourglass-split me-1"></i> En attente de traitement</div>
            </div>
        </a>
    </div>
    <div class="col-md-6">
        <a href="#section-traitees" style="text-decoration:none;">
            <div class="p-4 rounded-3 text-white" style="background: linear-gradient(135deg, #28a745, #20c997); cursor:pointer; transition: opacity .2s;" onmouseover="this.style.opacity='.85'" onmouseout="this.style.opacity='1'">
                <div style="font-size:2rem; font-weight:700;">{{ $traitees->count() }}</div>
                <div><i class="bi bi-check-circle me-1"></i> Traitées récemment</div>
            </div>
        </a>
    </div>
</div>

{{-- En attente --}}
<div class="card mb-4" id="section-attente">
    <div class="card-body">
        <h6 class="fw-bold mb-3">
            <i class="bi bi-hourglass-split me-2 text-warning"></i>En attente
        </h6>
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Étudiante</th>
                    <th>Chambre actuelle</th>
                    <th>Chambre demandée</th>
                    <th>Motif</th>
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
                    <td>
                        @if($demande->chambreActuelle)
                            <span class="badge bg-secondary">{{ $demande->chambreActuelle->numero }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if($demande->chambreDemandee)
                            <span class="badge bg-primary">{{ $demande->chambreDemandee->numero }}</span>
                            <br><small class="text-muted">{{ $demande->chambreDemandee->statut }}</small>
                        @else
                            <span class="text-muted">Non précisée</span>
                        @endif
                    </td>
                    <td><small>{{ Str::limit($demande->motif, 50) }}</small></td>
                    <td><small>{{ $demande->created_at->format('d/m/Y') }}</small></td>
                    <td>
                        {{-- Accepter --}}
                        <form method="POST"
                              action="{{ route('hebergement.changements.accepter', $demande) }}"
                              class="d-inline">
                            @csrf
                            <button class="btn btn-sm btn-success"
                                    onclick="return confirm('Accepter ce changement ?')">
                                <i class="bi bi-check-lg"></i> Accepter
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
                                          action="{{ route('hebergement.changements.refuser', $demande) }}">
                                        @csrf
                                        <div class="modal-body">
                                            <label class="form-label">Raison du refus</label>
                                            <textarea name="motif_refus" class="form-control" rows="4"
                                                      placeholder="Expliquez la raison du refus..."
                                                      required minlength="10"></textarea>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Annuler</button>
                                            <button type="submit" class="btn btn-danger">
                                                Confirmer le refus
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                        Aucune demande en attente.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Traitées --}}
<div class="card" id="section-traitees">
    <div class="card-body">
        <h6 class="fw-bold mb-3">
            <i class="bi bi-clock-history me-2 text-secondary"></i>Traitées récemment
        </h6>
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Étudiante</th>
                    <th>Chambre actuelle</th>
                    <th>Chambre demandée</th>
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
                    <td>{{ $demande->chambreActuelle->numero ?? '-' }}</td>
                    <td>{{ $demande->chambreDemandee->numero ?? '-' }}</td>
                    <td>
                        @if($demande->statut === 'acceptee')
                            <span class="badge bg-success">Acceptée</span>
                        @else
                            <span class="badge bg-danger">Refusée</span>
                        @endif
                    </td>
                    <td><small>{{ $demande->motif_refus ?? '-' }}</small></td>
                    <td><small>{{ $demande->updated_at->format('d/m/Y') }}</small></td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                        Aucune demande traitée.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection