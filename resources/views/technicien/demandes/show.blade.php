@extends('layouts.app')

@section('page-title', 'Traiter la demande #{{ $maintenance->id }}')

@section('content')
<div class="container-fluid">

    {{-- Retour --}}
    <a href="{{ route('technicien.demandes') }}" class="btn btn-sm btn-outline-secondary rounded-pill mb-4">
        <i class="bi bi-arrow-left me-1"></i>Retour aux demandes
    </a>

    {{-- En-tête demande --}}
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex flex-wrap align-items-start justify-content-between gap-3">
                <div>
                    <div class="d-flex flex-wrap gap-2 mb-2">
                        <span class="text-muted" style="font-size:.8rem;">#{{ $maintenance->id }}</span>

                        @if($maintenance->statut === 'en_attente')
                            <span class="badge bg-warning text-dark">En attente</span>
                        @elseif($maintenance->statut === 'en_cours')
                            <span class="badge bg-primary">En cours</span>
                        @else
                            <span class="badge bg-success">Terminée</span>
                        @endif

                        @if($maintenance->urgence === 'urgente')
                            <span class="badge bg-danger">
                                <i class="bi bi-exclamation-triangle me-1"></i>Urgente
                            </span>
                        @endif
                    </div>

                    <h5 class="fw-bold mb-2">{{ $maintenance->description }}</h5>

                    <div class="d-flex flex-wrap gap-3 text-muted" style="font-size:.85rem;">
                        <span><i class="bi bi-person me-1"></i>{{ $maintenance->etudiante->name ?? '-' }}</span>
                        <span><i class="bi bi-envelope me-1"></i>{{ $maintenance->etudiante->email ?? '-' }}</span>
                        <span><i class="bi bi-door-closed me-1"></i>Chambre {{ $maintenance->chambre->numero ?? '-' }}</span>
                        <span><i class="bi bi-calendar me-1"></i>{{ $maintenance->date_signalement?->format('d/m/Y') }}</span>
                        <span><i class="bi bi-tools me-1"></i>{{ ucfirst($maintenance->type) }}</span>
                    </div>
                </div>
            </div>

            {{-- Matériels déjà utilisés --}}
            @if($maintenance->materiels->count())
            <div class="mt-3 pt-3 border-top">
                <div class="text-muted mb-2" style="font-size:.75rem;font-weight:700;text-transform:uppercase;">
                    Matériel utilisé
                </div>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($maintenance->materiels as $m)
                    <span class="badge bg-light text-dark border" style="font-size:.78rem;">
                        <i class="bi bi-box me-1"></i>{{ $m->nom_materiel }} ×{{ $m->quantite }}
                        @if($m->stock_epuise)
                            <span class="text-danger ms-1">⚠ épuisé</span>
                        @endif
                    </span>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Incidents signalés --}}
            @if($maintenance->materiels->where('description_incident', '!=', null)->count())
            <div class="mt-3 p-3 rounded" style="background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.2);">
                <div class="text-danger fw-bold mb-1" style="font-size:.78rem;text-transform:uppercase;">
                    <i class="bi bi-exclamation-triangle me-1"></i>Incidents signalés
                </div>
                @foreach($maintenance->materiels->whereNotNull('description_incident') as $m)
                <div style="font-size:.85rem;color:#991b1b;">
                    <strong>{{ $m->nom_materiel }}</strong> — {{ $m->description_incident }}
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    <div class="row g-4">

        {{-- ① Mettre à jour le statut --}}
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-arrow-repeat me-2 text-primary"></i>Mettre à jour le statut</h6>
                    <small class="text-muted">Obligatoire lors du traitement</small>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('technicien.demandes.traiter', $maintenance->id) }}">
                        @csrf
                        <input type="hidden" name="statut" id="statutInput" value="{{ $maintenance->statut }}">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nouveau statut</label>
                            <select name="statut" class="form-select" required>
                                <option value="en_attente" {{ $maintenance->statut === 'en_attente' ? 'selected' : '' }}>
                                    En attente
                                </option>
                                <option value="en_cours" {{ $maintenance->statut === 'en_cours' ? 'selected' : '' }}>
                                    En cours d'intervention
                                </option>
                                <option value="terminee" {{ $maintenance->statut === 'terminee' ? 'selected' : '' }}>
                                    Terminée
                                </option>
                            </select>
                        </div>

                        <div class="alert alert-info py-2" style="font-size:.82rem;">
                            <i class="bi bi-bell me-1"></i>
                            Passer à <strong>Terminée</strong> notifie automatiquement l'étudiante.
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-floppy me-1"></i>Enregistrer le statut
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- ② Saisir matériel --}}
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-box-seam me-2 text-warning"></i>Saisir matériel utilisé</h6>
                    <small class="text-muted">Décrémente le stock automatiquement</small>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('technicien.demandes.traiter', $maintenance->id) }}">
                        @csrf
                        <input type="hidden" name="statut" value="{{ $maintenance->statut }}">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Désignation du matériel</label>
                            <input type="text" name="materiels[0][nom_materiel]"
                                   class="form-control" placeholder="ex: câble électrique, charnière…" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Quantité</label>
                            <input type="number" name="materiels[0][quantite]"
                                   class="form-control" min="1" value="1" required>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="stock_epuise" id="stockEpuise">
                            <label class="form-check-label" for="stockEpuise" style="font-size:.85rem;">
                                <i class="bi bi-exclamation-triangle text-warning me-1"></i>Signaler stock épuisé
                            </label>
                        </div>

                        <button type="submit" class="btn btn-warning text-white w-100">
                            <i class="bi bi-plus-lg me-1"></i>Ajouter le matériel
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- ③ Signaler un incident --}}
        <div class="col-12">
            <div class="card border-danger">
                <div class="card-header" style="background:rgba(239,68,68,.05);">
                    <h6 class="mb-0 text-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>Signaler un incident (casse outil)
                    </h6>
                    <small class="text-danger">Optionnel — en cas de casse ou problème</small>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('technicien.incidents.store') }}">
                        @csrf
                        <input type="hidden" name="maintenance_id" value="{{ $maintenance->id }}">

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Matériel concerné</label>
                                <input type="text" name="nom_materiel"
                                       class="form-control" placeholder="ex: tournevis, ventouse…" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Quantité</label>
                                <input type="number" name="quantite"
                                       class="form-control" min="1" value="1" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Description de l'incident</label>
                                <textarea name="description_incident" class="form-control"
                                          rows="2" placeholder="Décrivez les circonstances…" required></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-flag me-1"></i>Signaler l'incident
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection