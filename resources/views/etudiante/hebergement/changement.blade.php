@extends('layouts.app')
@section('page-title', 'Changement de chambre')
@section('content')

{{-- Titre --}}
<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('etudiante.hebergement.renouvellement') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i>
    </a>
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
    {{-- Deux cartes cliquables --}}
<div class="row g-3 mb-3">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100 carte-toggle active-carte"
             style="cursor:pointer; border-left: 4px solid #2d6a9f !important;"
             onclick="toggleSection('section-chambres', this)">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                     style="width:48px;height:48px;background:#e8f4fd;">
                    <i class="bi bi-building" style="font-size:1.4rem;color:#2d6a9f;"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="fw-bold">Chambres disponibles</div>
                    <div class="text-muted small">{{ $chambresDisponibles->total() ?? $chambresDisponibles->count() }} chambre(s)</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100 carte-toggle"
             style="cursor:pointer; border-left: 4px solid #fd7e14 !important;"
             onclick="toggleSection('section-demandes', this)">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                     style="width:48px;height:48px;background:#fff3e8;">
                    <i class="bi bi-clock-history" style="font-size:1.4rem;color:#fd7e14;"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="fw-bold">Mes demandes de changement</div>
                    <div class="text-muted small">{{ $demandesChangement->count() }} demande(s)</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Liste des chambres disponibles --}}
<div id="section-chambres" class="mb-4">
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

            {{-- Barre de recherche --}}
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
                    @endforeach
                </tbody>
            </table>

            {{-- Modals "Demander un changement" — HORS du tableau --}}
            @foreach($chambresDisponibles as $chambre)
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

            {{-- Message aucun résultat --}}
            <div id="noResult" class="text-center text-muted py-3" style="display:none;">
                <i class="bi bi-search me-1"></i> Aucune chambre trouvée.
            </div>

            <div class="mt-3">{{ $chambresDisponibles->links() }}</div>
        @endif
    </div>
</div>
</div>  {{-- ferme section-chambres --}}

{{-- Historique changements --}}
<div id="section-demandes" style="display:none;" class="mb-4">
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
                    <th>Actions</th>
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
                    <td>
                        @if($d->statut === 'en_attente')
                            <button type="button" class="btn btn-sm btn-outline-primary mb-1"
                                    data-bs-toggle="modal" data-bs-target="#modalEdit{{ $d->id }}">
                                <i class="bi bi-pencil"></i>
                            </button>

                            <form action="{{ route('etudiante.changement.annuler', $d->id) }}" method="POST"
                                  class="d-inline" onsubmit="return confirm('Annuler cette demande de changement ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-x-circle"></i>
                                </button>
                            </form>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-3">Aucune demande effectuée.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</div> {{-- ferme section-demandes --}}

{{-- Modals de modification — sélection en cascade Bloc > Type > Étage > Chambre --}}
@php
    $blocsDisponibles = $chambresDisponibles->pluck('bloc')->unique()->sort()->values();
@endphp
@foreach($demandesChangement as $d)
    @if($d->statut === 'en_attente')
    <div class="modal fade" id="modalEdit{{ $d->id }}" tabindex="-1"
         data-etage="{{ optional($d->chambreDemandee)->etage }}"
         data-chambre-id="{{ $d->chambre_demandee_id }}">
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow">
                <div class="modal-header" style="background: linear-gradient(135deg, #1a3c5e, #2d6a9f);">
                    <h5 class="modal-title text-white">
                        <i class="bi bi-pencil me-2"></i>Modifier ma demande
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('etudiante.changement.modifier', $d->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Bloc</label>
                            <select class="form-select select-bloc" data-target="{{ $d->id }}" required>
                                <option value="">-- Choisir --</option>
                                @foreach($blocsDisponibles as $bloc)
                                    <option value="{{ $bloc }}"
                                        {{ optional($d->chambreDemandee)->bloc == $bloc ? 'selected' : '' }}>
                                        Bloc {{ $bloc }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Type de chambre</label>
                            <select class="form-select select-type" data-target="{{ $d->id }}" required>
                                <option value="">-- Choisir --</option>
                                <option value="individuelle" {{ optional($d->chambreDemandee)->type == 'individuelle' ? 'selected' : '' }}>Individuelle</option>
                                <option value="double" {{ optional($d->chambreDemandee)->type == 'double' ? 'selected' : '' }}>Double</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Étage</label>
                            <select class="form-select select-etage" data-target="{{ $d->id }}" required>
                                <option value="">-- Choisir bloc et type d'abord --</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Chambre disponible</label>
                            <select name="chambre_demandee_id" class="form-select select-chambre" data-target="{{ $d->id }}" required>
                                <option value="">-- Choisir un étage d'abord --</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Motif du changement</label>
                            <textarea name="motif" class="form-control" rows="3" maxlength="500" required>{{ $d->motif }}</textarea>
                        </div>

                        <div class="mb-3 justificatif-bloc" data-target="{{ $d->id }}" style="display:none;">
                            <label class="form-label fw-semibold">
                                Justificatif <span class="badge bg-danger">Obligatoire pour une chambre individuelle</span>
                            </label>
                            <input type="file" name="justificatif" class="form-control" accept=".pdf">
                            <div class="form-text">Laisser vide pour garder le fichier actuel.</div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-sm text-white" style="background:#fd7e14;">
                            <i class="bi bi-save me-1"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
@endforeach

{{-- Script recherche + cascade --}}
<script>
function toggleSection(id, carte) {
    const section = document.getElementById(id);
    const isOpen = section.style.display !== 'none';
    document.querySelectorAll('#section-chambres, #section-demandes').forEach(s => s.style.display = 'none');
    document.querySelectorAll('.carte-toggle').forEach(c => c.classList.remove('active-carte'));
    if (!isOpen) {
        section.style.display = 'block';
        carte.classList.add('active-carte');
        section.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}
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

    // ---- Sélection en cascade Bloc > Type > Étage > Chambre (modals d'édition) ----
    const chambresData = [
        @foreach($chambresDisponibles as $chambre)
        { id: {{ $chambre->id }}, numero: "{{ $chambre->numero }}", bloc: "{{ $chambre->bloc }}", type: "{{ $chambre->type }}", etage: "{{ $chambre->etage }}" },
        @endforeach
    ];

    function getModalElements(id) {
        return {
            bloc: document.querySelector(`.select-bloc[data-target="${id}"]`),
            type: document.querySelector(`.select-type[data-target="${id}"]`),
            etage: document.querySelector(`.select-etage[data-target="${id}"]`),
            chambre: document.querySelector(`.select-chambre[data-target="${id}"]`),
            justificatifBloc: document.querySelector(`.justificatif-bloc[data-target="${id}"]`),
        };
    }

    function updateEtages(id, preselectEtage = null, preselectChambreId = null) {
        const el = getModalElements(id);
        const bloc = el.bloc.value;
        const type = el.type.value;

        el.etage.innerHTML = '<option value="">-- Choisir --</option>';
        el.chambre.innerHTML = '<option value="">-- Choisir un étage d\'abord --</option>';

        if (!bloc || !type) {
            toggleJustificatif(id);
            return;
        }

        const etages = [...new Set(
            chambresData.filter(c => c.bloc === bloc && c.type === type).map(c => c.etage)
        )];

        etages.forEach(etage => {
            const opt = document.createElement('option');
            opt.value = etage;
            opt.textContent = 'Étage ' + etage;
            if (preselectEtage !== null && String(etage) === String(preselectEtage)) opt.selected = true;
            el.etage.appendChild(opt);
        });

        toggleJustificatif(id);
        updateChambres(id, preselectChambreId);
    }

    function updateChambres(id, preselectChambreId = null) {
        const el = getModalElements(id);
        const bloc = el.bloc.value;
        const type = el.type.value;
        const etage = el.etage.value;

        el.chambre.innerHTML = '<option value="">-- Choisir --</option>';
        if (!bloc || !type || !etage) return;

        const chambres = chambresData.filter(c => c.bloc === bloc && c.type === type && String(c.etage) === String(etage));

        chambres.forEach(c => {
            const opt = document.createElement('option');
            opt.value = c.id;
            opt.textContent = 'Chambre ' + c.numero;
            if (preselectChambreId !== null && String(c.id) === String(preselectChambreId)) opt.selected = true;
            el.chambre.appendChild(opt);
        });
    }

    function toggleJustificatif(id) {
        const el = getModalElements(id);
        if (!el.justificatifBloc) return;
        el.justificatifBloc.style.display = el.type.value === 'individuelle' ? 'block' : 'none';
    }

    document.querySelectorAll('.select-bloc, .select-type').forEach(select => {
        select.addEventListener('change', function () {
            updateEtages(this.dataset.target);
        });
    });

    document.querySelectorAll('.select-etage').forEach(select => {
        select.addEventListener('change', function () {
            updateChambres(this.dataset.target);
        });
    });

    // Pré-remplissage à l'ouverture du modal (garde la chambre déjà choisie)
    document.querySelectorAll('[id^="modalEdit"]').forEach(modal => {
        modal.addEventListener('shown.bs.modal', function () {
            const id = this.id.replace('modalEdit', '');
            updateEtages(id, this.dataset.etage, this.dataset.chambreId);
        });
    });
</script>
<style>
.carte-toggle { transition: box-shadow 0.2s; }
.carte-toggle:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.10) !important; }
.active-carte { box-shadow: 0 4px 20px rgba(45,106,159,0.18) !important; }
</style>

@endsection