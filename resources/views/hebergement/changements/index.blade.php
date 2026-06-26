@extends('layouts.app')
@section('page-title', 'Demandes de Changement')

@section('content')



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

{{-- Filtres --}}
<div class="card mb-4">
    <div class="card-body">
        <div class="row g-2 align-items-center">
            <div class="col-md-3">
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input type="text" id="filterEtudiante" class="form-control" placeholder="Nom ou matricule...">
                </div>
            </div>
            <div class="col-md-2">
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><i class="bi bi-hash"></i></span>
                    <input type="text" id="filterNumero" class="form-control" placeholder="N° chambre...">
                </div>
            </div>
            <div class="col-md-2">
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><i class="bi bi-building"></i></span>
                    <input type="text" id="filterBloc" class="form-control" placeholder="Bloc...">
                </div>
            </div>
            <div class="col-md-2">
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><i class="bi bi-layers"></i></span>
                    <input type="text" id="filterEtage" class="form-control" placeholder="Étage...">
                </div>
            </div>
            <div class="col-md-3">
                <button class="btn btn-sm btn-outline-secondary w-100" id="resetFilters">
                    <i class="bi bi-x-circle me-1"></i> Réinitialiser
                </button>
            </div>
        </div>
    </div>
</div>

{{-- En attente --}}
<div class="card mb-4" id="section-attente">
    <div class="card-body">
        <h6 class="fw-bold mb-3">
            <i class="bi bi-hourglass-split me-2 text-warning"></i>En attente
        </h6>
        <table class="table table-hover mb-0" id="tableAttente">
            <thead class="table-light">
                <tr>
                    <th>Étudiante</th>
                    <th>Chambre actuelle</th>
                    <th>Chambre demandée</th>
                    <th>Motif</th>
                    <th>Justificatif</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($enAttente as $demande)
                <tr class="demande-row"
                    data-etudiante="{{ strtolower($demande->etudiante->name . ' ' . $demande->etudiante->matricule) }}"
                    data-numero="{{ strtolower($demande->chambreActuelle->numero ?? '') . ' ' . strtolower($demande->chambreDemandee->numero ?? '') }}"
                    data-bloc="{{ strtolower($demande->chambreActuelle->bloc ?? '') . ' ' . strtolower($demande->chambreDemandee->bloc ?? '') }}"
                    data-etage="{{ ($demande->chambreActuelle->etage ?? '') . ' ' . ($demande->chambreDemandee->etage ?? '') }}">
                    <td>
                        <strong>{{ $demande->etudiante->name }}</strong><br>
                        <small class="text-muted">{{ $demande->etudiante->matricule }}</small>
                    </td>
                    <td>
                        @if($demande->chambreActuelle)
                            <span class="badge bg-secondary">{{ $demande->chambreActuelle->numero }}</span>
                            <br><small class="text-muted">Bloc {{ $demande->chambreActuelle->bloc }} — Ét.{{ $demande->chambreActuelle->etage }}</small>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if($demande->chambreDemandee)
                            <span class="badge bg-primary">{{ $demande->chambreDemandee->numero }}</span>
                            <br><small class="text-muted">Bloc {{ $demande->chambreDemandee->bloc }} — Ét.{{ $demande->chambreDemandee->etage }}</small>
                        @else
                            <span class="text-muted">Non précisée</span>
                        @endif
                    </td>
                    <td><small>{{ Str::limit($demande->motif, 50) }}</small></td>
                    <td>
                        @if($demande->justificatif)
                            <a href="{{ asset('storage/'.$demande->justificatif) }}"
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
        data-bs-target="#accepterModal{{ $demande->id }}">
    <i class="bi bi-check-lg"></i> Accepter
</button>

<div class="modal fade" id="accepterModal{{ $demande->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="bi bi-clipboard-check me-2"></i>Prise en charge — {{ $demande->etudiante->name }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('hebergement.changements.accepter', $demande) }}">
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
                        <i class="bi bi-check-lg me-1"></i> Accepter et générer les documents
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#refusModal{{ $demande->id }}">
                            <i class="bi bi-x-lg"></i> Refuser
                        </button>
                        <div class="modal fade" id="refusModal{{ $demande->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Motif du refus</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="POST" action="{{ route('hebergement.changements.refuser', $demande) }}">
                                        @csrf
                                        <div class="modal-body">
                                            <label class="form-label">Raison du refus</label>
                                            <textarea name="motif_refus" class="form-control" rows="4"
                                                      placeholder="Expliquez la raison du refus..."
                                                      required minlength="10"></textarea>
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
                    <td colspan="7" class="text-center text-muted py-4">Aucune demande en attente.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div id="noResultAttente" class="text-center text-muted py-3" style="display:none;">
            <i class="bi bi-search me-1"></i> Aucun résultat trouvé.
        </div>
    </div>
</div>

{{-- Traitées --}}
<div class="card" id="section-traitees">
    <div class="card-body">
        <h6 class="fw-bold mb-3">
            <i class="bi bi-clock-history me-2 text-secondary"></i>Traitées récemment
        </h6>
        <table class="table table-hover mb-0" id="tableTraitees">
            <thead class="table-light">
                <tr>
                    <th>Étudiante</th>
                    <th>Chambre actuelle</th>
                    <th>Chambre demandée</th>
                    <th>Motif</th>
                    <th>Justificatif</th>
                    <th>Statut</th>
                    <th>Motif refus</th>
                    <th>Documents</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($traitees as $demande)
                <tr class="traitee-row"
                    data-etudiante="{{ strtolower($demande->etudiante->name . ' ' . $demande->etudiante->matricule) }}"
                    data-numero="{{ strtolower($demande->chambreActuelle->numero ?? '') . ' ' . strtolower($demande->chambreDemandee->numero ?? '') }}"
                    data-bloc="{{ strtolower($demande->chambreActuelle->bloc ?? '') . ' ' . strtolower($demande->chambreDemandee->bloc ?? '') }}"
                    data-etage="{{ ($demande->chambreActuelle->etage ?? '') . ' ' . ($demande->chambreDemandee->etage ?? '') }}">
                    <td>
                        <strong>{{ $demande->etudiante->name }}</strong><br>
                        <small class="text-muted">{{ $demande->etudiante->matricule }}</small>
                    </td>
                    <td>
                        {{ $demande->chambreActuelle->numero ?? '-' }}
                        @if($demande->chambreActuelle)
                            <br><small class="text-muted">Bloc {{ $demande->chambreActuelle->bloc }} — Ét.{{ $demande->chambreActuelle->etage }}</small>
                        @endif
                    </td>
                    <td>
                        {{ $demande->chambreDemandee->numero ?? '-' }}
                        @if($demande->chambreDemandee)
                            <br><small class="text-muted">Bloc {{ $demande->chambreDemandee->bloc }} — Ét.{{ $demande->chambreDemandee->etage }}</small>
                        @endif
                    </td>
                    <td><small>{{ Str::limit($demande->motif, 50) }}</small></td>
                    <td>
                        @if($demande->justificatif)
                            <a href="{{ asset('storage/'.$demande->justificatif) }}"
                               target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-file-earmark me-1"></i>Voir
                            </a>
                        @else
                            <span class="text-muted">Aucun</span>
                        @endif
                    </td>
                    <td>
                        @if($demande->statut === 'acceptee')
                            <span class="badge bg-success">Acceptée</span>
                        @else
                            <span class="badge bg-danger">Refusée</span>
                        @endif
                    </td>
                   <td><small>{{ $demande->motif_refus ?? '-' }}</small></td>
<td>
    @if($demande->statut === 'acceptee')
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
                    <form method="POST" action="{{ route('hebergement.changements.modifier-pec', $demande) }}">
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
<tr>
    <td colspan="9" class="text-center text-muted py-4">Aucune demande traitée.</td>
</tr>
@endforelse
            </tbody>
        </table>
        <div id="noResultTraitees" class="text-center text-muted py-3" style="display:none;">
            <i class="bi bi-search me-1"></i> Aucun résultat trouvé.
        </div>
    </div>
</div>

<script>
const inputs = ['filterEtudiante', 'filterNumero', 'filterBloc', 'filterEtage'];

function filtrer() {
    const etudiante = document.getElementById('filterEtudiante').value.toLowerCase().trim();
    const numero    = document.getElementById('filterNumero').value.toLowerCase().trim();
    const bloc      = document.getElementById('filterBloc').value.toLowerCase().trim();
    const etage     = document.getElementById('filterEtage').value.toLowerCase().trim();

    ['demande-row', 'traitee-row'].forEach(cls => {
        const rows = document.querySelectorAll('.' + cls);
        const noResult = cls === 'demande-row'
            ? document.getElementById('noResultAttente')
            : document.getElementById('noResultTraitees');
        let visible = 0;

        rows.forEach(row => {
            const match =
                row.dataset.etudiante.includes(etudiante) &&
                row.dataset.numero.includes(numero) &&
                row.dataset.bloc.includes(bloc) &&
                row.dataset.etage.includes(etage);

            row.style.display = match ? '' : 'none';
            if (match) visible++;
        });

        noResult.style.display = visible === 0 ? 'block' : 'none';
    });
}

inputs.forEach(id => {
    document.getElementById(id).addEventListener('input', filtrer);
});

document.getElementById('resetFilters').addEventListener('click', function () {
    inputs.forEach(id => document.getElementById(id).value = '');
    filtrer();
});
</script>

@endsection