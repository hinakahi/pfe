@extends('layouts.app')
@section('page-title', 'Demandes de Renouvellement')

@section('content')



{{-- Stats cliquables --}}
<div class="row g-3 mb-3">
    <div class="col-md-6">
        <a href="#enAttente" style="text-decoration:none;">
            <div class="p-4 rounded-3 text-white" style="background: linear-gradient(135deg, #fd7e14, #ffc107); cursor:pointer; transition: opacity .2s;" onmouseover="this.style.opacity='.85'" onmouseout="this.style.opacity='1'">
                <div style="font-size:2rem; font-weight:700;">{{ $enAttente->count() }}</div>
                <div><i class="bi bi-hourglass-split me-1"></i> En attente de traitement</div>
            </div>
        </a>
    </div>
    <div class="col-md-6">
        <a href="#traitees" style="text-decoration:none;">
            <div class="p-4 rounded-3 text-white" style="background: linear-gradient(135deg, #28a745, #20c997); cursor:pointer; transition: opacity .2s;" onmouseover="this.style.opacity='.85'" onmouseout="this.style.opacity='1'">
                <div style="font-size:2rem; font-weight:700;">{{ $traitees->count() }}</div>
                <div><i class="bi bi-check-circle me-1"></i> Traitées récemment</div>
            </div>
        </a>
    </div>
</div>

{{-- Barre de recherche --}}
<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body">
        <div class="row g-2 align-items-center">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input type="text" id="searchInput" class="form-control"
                           placeholder="Nom ou matricule...">
                </div>
            </div>
            <div class="col-md-2">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-hash"></i></span>
                    <input type="text" id="filterChambre" class="form-control"
                           placeholder="N° chambre...">
                </div>
            </div>
            <div class="col-md-2">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-building"></i></span>
                    <input type="text" id="filterBloc" class="form-control"
                           placeholder="Bloc...">
                </div>
            </div>
            <div class="col-md-2">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-layers"></i></span>
                    <input type="text" id="filterEtage" class="form-control"
                           placeholder="Étage...">
                </div>
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-secondary w-100" onclick="resetFilters()">
                    <i class="bi bi-x-circle me-1"></i> Réinitialiser
                </button>
            </div>
        </div>
    </div>
</div>

{{-- En attente --}}
<div class="card mb-4" id="enAttente">
    <div class="card-body">
        <h6 class="fw-bold mb-3"><i class="bi bi-hourglass-split me-2 text-warning"></i>En attente</h6>
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Étudiante</th>
                    <th>Chambre </th>
                    <th>Justif. Scolarité</th>
                    <th>Justif. Paiement</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="tbodyAttente">
                @forelse($enAttente as $demande)
                <tr
                    data-nom="{{ strtolower($demande->etudiante->name) }}"
                    data-matricule="{{ strtolower($demande->etudiante->matricule ?? '') }}"
                    data-chambre="{{ strtolower($demande->chambre->numero ?? '') }}"
                    data-bloc="{{ strtolower($demande->chambre->bloc ?? '') }}"
                    data-etage="{{ strtolower($demande->chambre->etage ?? '') }}"
                >
                    <td>
                        <strong>{{ $demande->etudiante->name }}</strong><br>
                        <small class="text-muted">{{ $demande->etudiante->matricule }}</small>
                    </td>
                    <td>
                        <strong>{{ $demande->chambre->numero ?? '-' }}</strong>
                        — Bloc {{ $demande->chambre->bloc ?? '-' }}<br>
                        <small class="text-muted">Étage {{ $demande->chambre->etage ?? '-' }}</small>
                    </td>
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
                        <button class="btn btn-sm btn-success"
        data-bs-toggle="modal"
        data-bs-target="#validerModal{{ $demande->id }}">
    <i class="bi bi-check-lg"></i> Valider
</button>

<div class="modal fade" id="validerModal{{ $demande->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="bi bi-clipboard-check me-2"></i>Prise en charge — {{ $demande->etudiante->name }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('hebergement.renouvellements.valider', $demande) }}">
                @csrf
                <div class="modal-body">
                    <p class="text-muted small">Vérifiez et ajustez les quantités avant validation.</p>

                    <h6 class="fw-bold mt-3">Matériel individuel</h6>
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr><th>N°</th><th>Désignation</th><th style="width:100px">Quantité</th></tr>
                        </thead>
                        <tbody>
                            @php
                                $defautIndividuel = [
                                    'Clé', 'Couette', 'Couverture', 'Draps', 'Oreiller', 'Couvre Oreiller',
                                    'Matelas', 'Couvre matelas', 'Sommier', 'Chaise', 'Barquette', 'Cuillère + Fourchette'
                                ];
                            @endphp
                            @foreach($defautIndividuel as $i => $designation)
                            <tr>
                                <td>{{ sprintf('%02d', $i+1) }}</td>
                                <td>
                                    <input type="text" name="individuel[{{ $i }}][designation]"
                                           value="{{ $designation }}" class="form-control form-control-sm">
                                </td>
                                <td>
                                    <input type="text" name="individuel[{{ $i }}][quantite]"
                                           value="01" class="form-control form-control-sm">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <h6 class="fw-bold mt-3">Matériel collectif</h6>
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr><th>N°</th><th>Désignation</th><th style="width:100px">Quantité</th></tr>
                        </thead>
                        <tbody>
                            @php
                                $defautCollectif = [
                                    'Corbeille', 'Interrupteur', 'Mélangeur douche', 'Mélangeur lavabo',
                                    'Prise courant', 'Rideaux', 'Table scolaire', 'Vachette',
                                    'Vitre fenêtre chambre', 'Vitre fenêtre salle d\'eau'
                                ];
                            @endphp
                            @foreach($defautCollectif as $i => $designation)
                            <tr>
                                <td>{{ sprintf('%02d', $i+1) }}</td>
                                <td>
                                    <input type="text" name="collectif[{{ $i }}][designation]"
                                           value="{{ $designation }}" class="form-control form-control-sm">
                                </td>
                                <td>
                                    <input type="text" name="collectif[{{ $i }}][quantite]"
                                           value="01" class="form-control form-control-sm">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-lg me-1"></i> Valider et générer les documents
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

                        <button class="btn btn-sm btn-danger"
                                data-bs-toggle="modal"
                                data-bs-target="#refusModal{{ $demande->id }}">
                            <i class="bi bi-x-lg"></i> Refuser
                        </button>

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
                <tr id="emptyAttente">
                    <td colspan="6" class="text-center text-muted py-4">Aucune demande en attente.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Traitées --}}
<div class="card" id="traitees">
    <div class="card-body">
        <h6 class="fw-bold mb-3"><i class="bi bi-clock-history me-2 text-secondary"></i>Traitées récemment</h6>
        <table class="table table-hover mb-0">
            <thead class="table-light">
    <tr>
        <th>Étudiante</th>
        <th>Chambre</th>
        <th>Justif. Scolarité</th>
        <th>Justif. Paiement</th>
        <th>Statut</th>
        <th>Motif refus</th>
        <th>Documents</th>
        <th>Date</th>
    </tr>
</thead>
            <tbody id="tbodyTraitees">
                @forelse($traitees as $demande)
                <tr
                    data-nom="{{ strtolower($demande->etudiante->name) }}"
                    data-matricule="{{ strtolower($demande->etudiante->matricule ?? '') }}"
                    data-chambre="{{ strtolower($demande->chambre->numero ?? '') }}"
                    data-bloc="{{ strtolower($demande->chambre->bloc ?? '') }}"
                    data-etage="{{ strtolower($demande->chambre->etage ?? '') }}"
                >
                    <td>
                        <strong>{{ $demande->etudiante->name }}</strong><br>
                        <small class="text-muted">{{ $demande->etudiante->matricule }}</small>
                    </td>
                    <td>
                        <strong>{{ $demande->chambre->numero ?? '-' }}</strong>
                        — Bloc {{ $demande->chambre->bloc ?? '-' }}<br>
                        <small class="text-muted">Étage {{ $demande->chambre->etage ?? '-' }}</small>
                    </td>
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
                    <td>
                        @if($demande->statut === 'validee')
                            <span class="badge bg-success">Validée</span>
                        @else
                            <span class="badge bg-danger">Refusée</span>
                        @endif
                    </td>
                    <td><small>{{ $demande->motif_refus ?? '-' }}</small></td>
                     <td>
    @if($demande->statut === 'validee')
        @if($demande->decision_pdf)
        <a href="{{ asset('storage/'.$demande->decision_pdf) }}" target="_blank"
           class="btn btn-sm btn-outline-success mb-1" title="Décision de réadmission">
            <i class="bi bi-file-earmark-pdf"></i> Décision
        </a>
        @endif
        @if($demande->prise_en_charge_pdf)
        <a href="{{ asset('storage/'.$demande->prise_en_charge_pdf) }}" target="_blank"
           class="btn btn-sm btn-outline-success mb-1" title="Prise en charge">
            <i class="bi bi-file-earmark-pdf"></i> P.E.C
        </a>
        <button class="btn btn-sm btn-outline-warning mb-1"
                data-bs-toggle="modal" data-bs-target="#modifierPecModal{{ $demande->id }}">
            <i class="bi bi-pencil"></i> Modifier
        </button>

        <div class="modal fade" id="modifierPecModal{{ $demande->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title">
                            <i class="bi bi-pencil me-2"></i>Modifier la prise en charge — {{ $demande->etudiante->name }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form method="POST" action="{{ route('hebergement.renouvellements.modifier-pec', $demande) }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            @php
                                $individuelActuel = $demande->materiel_json['individuel'] ?? [];
                                $collectifActuel = $demande->materiel_json['collectif'] ?? [];
                            @endphp

                            <h6 class="fw-bold mt-3">Matériel individuel</h6>
                            <table class="table table-sm table-bordered">
                                <thead class="table-light">
                                    <tr><th>N°</th><th>Désignation</th><th style="width:100px">Quantité</th></tr>
                                </thead>
                                <tbody>
                                    @foreach($individuelActuel as $i => $item)
                                    <tr>
                                        <td>{{ sprintf('%02d', $i+1) }}</td>
                                        <td>
                                            <input type="text" name="individuel[{{ $i }}][designation]"
                                                   value="{{ $item['designation'] }}" class="form-control form-control-sm">
                                        </td>
                                        <td>
                                            <input type="text" name="individuel[{{ $i }}][quantite]"
                                                   value="{{ $item['quantite'] }}" class="form-control form-control-sm">
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <h6 class="fw-bold mt-3">Matériel collectif</h6>
                            <table class="table table-sm table-bordered">
                                <thead class="table-light">
                                    <tr><th>N°</th><th>Désignation</th><th style="width:100px">Quantité</th></tr>
                                </thead>
                                <tbody>
                                    @foreach($collectifActuel as $i => $item)
                                    <tr>
                                        <td>{{ sprintf('%02d', $i+1) }}</td>
                                        <td>
                                            <input type="text" name="collectif[{{ $i }}][designation]"
                                                   value="{{ $item['designation'] }}" class="form-control form-control-sm">
                                        </td>
                                        <td>
                                            <input type="text" name="collectif[{{ $i }}][quantite]"
                                                   value="{{ $item['quantite'] }}" class="form-control form-control-sm">
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-check-lg me-1"></i> Mettre à jour le PDF
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
    @else
        <span class="text-muted">-</span>
    @endif
</td>
                    <td><small>{{ $demande->updated_at->format('d/m/Y') }}</small></td>
                </tr>
                @empty
                <tr id="emptyTraitees">
                    <td colspan="8" class="text-center text-muted py-4">Aucune demande traitée.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
document.getElementById('searchInput').addEventListener('input', filterTables);
document.getElementById('filterChambre').addEventListener('input', filterTables);
document.getElementById('filterBloc').addEventListener('input', filterTables);
document.getElementById('filterEtage').addEventListener('input', filterTables);

function filterTables() {
    const search  = document.getElementById('searchInput').value.toLowerCase();
    const chambre = document.getElementById('filterChambre').value.toLowerCase();
    const bloc    = document.getElementById('filterBloc').value.toLowerCase();
    const etage   = document.getElementById('filterEtage').value.toLowerCase();

    filterRows('tbodyAttente', search, chambre, bloc, etage);
    filterRows('tbodyTraitees', search, chambre, bloc, etage);
}

function filterRows(tbodyId, search, chambre, bloc, etage) {
    const rows = document.querySelectorAll('#' + tbodyId + ' tr');
    rows.forEach(row => {
        if (row.id && row.id.startsWith('empty')) return;

        const matchSearch  = search  === '' || row.dataset.nom.includes(search) || row.dataset.matricule.includes(search);
        const matchChambre = chambre === '' || row.dataset.chambre.includes(chambre);
        const matchBloc    = bloc    === '' || row.dataset.bloc.includes(bloc);
        const matchEtage   = etage   === '' || row.dataset.etage.includes(etage);

        row.style.display = (matchSearch && matchChambre && matchBloc && matchEtage) ? '' : 'none';
    });
}

function resetFilters() {
    document.getElementById('searchInput').value   = '';
    document.getElementById('filterChambre').value = '';
    document.getElementById('filterBloc').value    = '';
    document.getElementById('filterEtage').value   = '';
    filterTables();
}
</script>

@endsection