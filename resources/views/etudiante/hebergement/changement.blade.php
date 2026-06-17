@extends('layouts.app')
@section('page-title', 'Changement de chambre')
@section('content')




{{-- Titre --}}
<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('etudiante.hebergement.renouvellement') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <h4 class="fw-bold mb-0">Changement de chambre</h4>
        <p class="text-muted mb-0 small">Choisissez une chambre disponible et soumettez votre demande.</p>
    </div>
</div>

{{-- Ma chambre actuelle --}}
@if($maChambre)
<div class="p-3 rounded-3 text-white mb-4" style="background: linear-gradient(135deg, #1a3c5e, #2d6a9f);">
    <div class="d-flex align-items-center gap-3">
        <i class="bi bi-door-open" style="font-size:2rem;"></i>
        <div>
            <div class="small opacity-75">Ma chambre actuelle</div>
            <div class="fw-bold fs-5">
                Chambre {{ $maChambre->numero }} — Bloc {{ $maChambre->bloc }} — Étage {{ $maChambre->etage }}
                <span class="badge ms-2" style="background:rgba(255,255,255,0.2);">
                    {{ ucfirst($maChambre->type) }}
                </span>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Vérification période --}}
@if(!$periodeChangement)
    <div class="alert alert-warning d-flex align-items-center gap-2 mb-4">
        <i class="bi bi-lock-fill fs-5"></i>
        <div>
            <strong>Période fermée</strong> — Les demandes de changement ne sont pas disponibles pour le moment.
            Veuillez attendre l'ouverture d'une période par l'administration.
        </div>
    </div>
@else
    <div class="alert alert-info d-flex align-items-center gap-2 mb-4 py-2">
        <i class="bi bi-info-circle me-1"></i>
        <span>Période ouverte jusqu'au <strong>{{ $periodeChangement->date_fin->format('d/m/Y') }}</strong></span>
    </div>
@endif

{{-- Liste des chambres disponibles --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <h6 class="fw-bold mb-3">
            <i class="bi bi-list-ul me-2 text-primary"></i>Chambres disponibles
        </h6>

        @if(!$periodeChangement)
            <div class="text-center text-muted py-4">
                <i class="bi bi-lock" style="font-size:2rem;"></i>
                <p class="mt-2 mb-0">Les chambres ne sont pas accessibles hors période.</p>
            </div>

        @elseif($chambresDisponibles->isEmpty())
            <div class="alert alert-warning py-2 small mb-0">
                <i class="bi bi-exclamation-triangle me-1"></i>
                Aucune chambre disponible pour le moment.
            </div>
        @else

            {{-- ✅ Barre de recherche --}}
            <div class="row g-2 mb-3">
                <div class="col-md-5">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" id="searchNumero" class="form-control"
                               placeholder="Rechercher par numéro...">
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><i class="bi bi-building"></i></span>
                        <input type="text" id="searchBloc" class="form-control"
                               placeholder="Rechercher par bloc...">
                    </div>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-sm btn-outline-secondary w-100" id="resetSearch">
                        <i class="bi bi-x-circle me-1"></i> Réinitialiser
                    </button>
                </div>
            </div>

            <table class="table table-hover mb-0" id="tableChambres">
                <thead class="table-light">
                    <tr>
                        <th>Numéro</th>
                        <th>Type</th>
                        <th>Bloc</th>
                        <th>Étage</th>
                        <th>Statut</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($chambresDisponibles as $chambre)
                    <tr class="chambre-row"
                        data-numero="{{ strtolower($chambre->numero) }}"
                        data-bloc="{{ strtolower($chambre->bloc) }}">
                        <td><strong>{{ $chambre->numero }}</strong></td>
                        <td>
                            @if($chambre->type === 'individuelle')
                                <span class="badge bg-info text-dark">Individuelle</span>
                            @else
                                <span class="badge" style="background:#6f42c1; color:#fff;">Double</span>
                            @endif
                        </td>
                        <td>{{ $chambre->bloc ?? '-' }}</td>
                        <td>{{ $chambre->etage }}</td>
                        <td><span class="badge bg-success">Disponible</span></td>
                        <td>
                            <button class="btn btn-sm text-white"
                                    style="background:#fd7e14;"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalChangement{{ $chambre->id }}">
                                <i class="bi bi-shuffle me-1"></i> Changer
                            </button>
                        </td>
                    </tr>

                    {{-- Modal pour cette chambre --}}
                    <div class="modal fade" id="modalChangement{{ $chambre->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content border-0 shadow">
                                <div class="modal-header" style="background: linear-gradient(135deg, #1a3c5e, #2d6a9f);">
                                    <h5 class="modal-title text-white">
                                        <i class="bi bi-shuffle me-2"></i>Demande de changement
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('etudiante.changement.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="chambre_actuelle_id" value="{{ $maChambre->id ?? '' }}">
                                    <input type="hidden" name="chambre_demandee_id" value="{{ $chambre->id }}">
                                    <div class="modal-body">
                                        <div class="rounded-2 px-3 py-2 small mb-3" style="background:#e8f4fd; color:#1a3c5e;">
                                            <div><i class="bi bi-door-closed me-1"></i> Actuelle : <strong>Chambre {{ $maChambre->numero ?? '-' }}</strong></div>
                                            <div><i class="bi bi-door-open me-1"></i> Demandée : <strong>Chambre {{ $chambre->numero }} — Bloc {{ $chambre->bloc }}</strong></div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Motif du changement</label>
                                            <textarea name="motif" class="form-control" rows="3"
                                                      placeholder="Expliquez la raison de votre demande..." required maxlength="500"></textarea>
                                        </div>
                                        @if($chambre->type === 'individuelle')
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">
                                                Justificatif <span class="badge bg-danger">Obligatoire</span>
                                            </label>
                                            <input type="file" name="justificatif" class="form-control" accept=".pdf" required>
                                            <div class="form-text">Format PDF uniquement, max 5 Mo.</div>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Annuler</button>
                                        <button type="submit" class="btn btn-sm text-white" style="background:#fd7e14;">
                                            <i class="bi bi-send me-1"></i> Envoyer la demande
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>

            {{-- Message aucun résultat --}}
            <div id="noResult" class="text-center text-muted py-3" style="display:none;">
                <i class="bi bi-search me-1"></i> Aucune chambre trouvée.
            </div>

            <div class="mt-3">{{ $chambresDisponibles->links() }}</div>
        @endif
    </div>
</div>

{{-- Historique changements --}}
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <h6 class="fw-bold mb-3">
            <i class="bi bi-clock-history me-2 text-secondary"></i>Mes demandes de changement
        </h6>
        <table class="table table-sm table-hover mb-0">
            <thead class="table-light">
    <tr>
        <th>Date</th>
        <th>Chambre actuelle</th>
        <th>Chambre demandée</th>
        <th>Statut</th>
        <th>Motif refus</th>
        <th>Documents</th>
    </tr>
</thead>
            <tbody>
                @forelse($demandesChangement as $d)
                <tr>
                    <td><small>{{ $d->created_at->format('d/m/Y') }}</small></td>
                    <td>
    {{ $d->chambreActuelle->numero ?? '-' }}
    @if($d->chambreActuelle)
        <small class="text-muted">— Bloc {{ $d->chambreActuelle->bloc }} Ét.{{ $d->chambreActuelle->etage }}</small>
    @endif
</td>
<td>
    {{ $d->chambreDemandee->numero ?? '-' }}
    @if($d->chambreDemandee)
        <small class="text-muted">— Bloc {{ $d->chambreDemandee->bloc }} Ét.{{ $d->chambreDemandee->etage }}</small>
    @endif
</td>
                    <td>
                        @if($d->statut === 'en_attente')
                            <span class="badge bg-warning text-dark">En attente</span>
                        @elseif($d->statut === 'acceptee')
                            <span class="badge bg-success">Acceptée</span>
                        @else
                            <span class="badge bg-danger">Refusée</span>
                        @endif
                    </td>
                  <td><small>{{ $d->motif_refus ?? '-' }}</small></td>
<td>
    @if($d->statut === 'acceptee' && ($d->decision_pdf || $d->prise_en_charge_pdf))
        @if($d->decision_pdf)
        <a href="{{ asset('storage/'.$d->decision_pdf) }}" target="_blank"
           class="btn btn-sm btn-outline-success mb-1" title="Décision de réadmission">
            <i class="bi bi-file-earmark-pdf"></i> Décision
        </a>
        @endif
        @if($d->prise_en_charge_pdf)
        <a href="{{ asset('storage/'.$d->prise_en_charge_pdf) }}" target="_blank"
           class="btn btn-sm btn-outline-success mb-1" title="Prise en charge">
            <i class="bi bi-file-earmark-pdf"></i> P.E.C
        </a>
        @endif
    @else
        <span class="text-muted">-</span>
    @endif
</td>
</tr>
@empty
<tr>
    <td colspan="6" class="text-center text-muted py-3">Aucune demande effectuée.</td>
</tr>
@endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Script recherche --}}
<script>
    const searchNumero = document.getElementById('searchNumero');
    const searchBloc   = document.getElementById('searchBloc');
    const resetBtn     = document.getElementById('resetSearch');
    const noResult     = document.getElementById('noResult');

    function filtrer() {
        const numero = searchNumero.value.toLowerCase().trim();
        const bloc   = searchBloc.value.toLowerCase().trim();
        const rows   = document.querySelectorAll('.chambre-row');
        let visible  = 0;

        rows.forEach(row => {
            const matchNumero = row.dataset.numero.includes(numero);
            const matchBloc   = row.dataset.bloc.includes(bloc);
            if (matchNumero && matchBloc) {
                row.style.display = '';
                visible++;
            } else {
                row.style.display = 'none';
            }
        });

        noResult.style.display = visible === 0 ? 'block' : 'none';
    }

    searchNumero.addEventListener('input', filtrer);
    searchBloc.addEventListener('input', filtrer);

    resetBtn.addEventListener('click', function() {
        searchNumero.value = '';
        searchBloc.value   = '';
        filtrer();
    });
</script>

@endsection