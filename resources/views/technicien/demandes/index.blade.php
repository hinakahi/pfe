@extends('layouts.app')

@section('page-title', 'Demandes de maintenance')

@section('content')
<div class="container-fluid">
    {{-- Cartes statistiques --}}
@php
    $nonTraitees = $demandes->whereIn('statut', ['en_attente', 'en_cours'])->count();
    $terminees = $demandes->where('statut', 'terminee')->count();
@endphp
<div class="row g-3 mb-4">
    <div class="col-md-6">
       <a href="?statut=non_traitees" style="text-decoration:none;">
            <div class="stat-card" style="background: linear-gradient(135deg, #f59e0b, #fbbf24); cursor:pointer;">
                <div class="number">{{ $nonTraitees }}</div>
                <div class="label"><i class="bi bi-hourglass-split me-1"></i>Non traitées (en attente + en cours)</div>
            </div>
        </a>
    </div>
    <div class="col-md-6">
        <a href="?statut=terminee" style="text-decoration:none;">
            <div class="stat-card" style="background: linear-gradient(135deg, #10b981, #34d399); cursor:pointer;">
                <div class="number">{{ $terminees }}</div>
                <div class="label"><i class="bi bi-check-circle me-1"></i>Traitées récemment</div>
            </div>
        </a>
    </div>
</div>

    {{-- Filtres --}}
    <div class="d-flex flex-wrap gap-2 mb-4">

        <div class="dropdown">
            <button class="btn btn-sm btn-outline-secondary rounded-pill dropdown-toggle" type="button" data-bs-toggle="dropdown">
                @php
    $statutLabels = [
        'tous'          => 'Tous les statuts',
        'en_attente'    => 'En attente',
        'en_cours'      => 'En cours',
        'terminee'      => 'Terminées',
        'non_traitees'  => 'Non traitées',
    ];
@endphp
                {{ $statutLabels[request('statut', 'tous')] }}
            </button>
            <ul class="dropdown-menu">
                @foreach([
                    'tous'       => 'Tous les statuts',
                    'en_attente' => 'En attente',
                    'en_cours'   => 'En cours',
                    'terminee'   => 'Terminées',
                ] as $val => $label)
                <li>
                    <a class="dropdown-item {{ request('statut', 'tous') === $val ? 'active' : '' }}"
                       href="?statut={{ $val }}&type={{ request('type', 'tous') }}">
                        {{ $label }}
                    </a>
                </li>
                @endforeach
            </ul>
        </div>

        <div class="dropdown">
            <button class="btn btn-sm btn-outline-secondary rounded-pill dropdown-toggle" type="button" data-bs-toggle="dropdown">
                @php
                    $typeLabels = ['tous'=>'Tous les types','electricite'=>'Électricité','plomberie'=>'Plomberie','menuiserie'=>'Menuiserie','chauffage'=>'Chauffage','autre'=>'Autre'];
                @endphp
                {{ $typeLabels[request('type', 'tous')] }}
            </button>
            <ul class="dropdown-menu">
                @foreach($typeLabels as $val => $label)
                <li>
                    <a class="dropdown-item {{ request('type', 'tous') === $val ? 'active' : '' }}"
                       href="?statut={{ request('statut', 'tous') }}&type={{ $val }}">
                        {{ $label }}
                    </a>
                </li>
                @endforeach
            </ul>
        </div>

    </div>

    {{-- Liste --}}
    <div class="d-flex flex-column gap-3">
    @forelse($demandes as $d)

        {{-- Couleur de fond selon statut --}}
        @php
            $borderColor = match($d->statut) {
                'en_attente' => '#ffc107',
                'en_cours'   => '#0d6efd',
                'terminee'   => '#198754',
                default      => '#dee2e6',
            };
        @endphp

        <div class="card shadow-sm" style="border-left: 4px solid {{ $borderColor }};">
            <div class="card-body py-3">
                <div class="d-flex flex-wrap align-items-start justify-content-between gap-3">

                    <div style="flex:1; min-width:240px;">

                        {{-- Ligne 1 : ID + Badges --}}
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                            <span class="text-muted fw-bold" style="font-size:.78rem;">#{{ $d->id }}</span>

                            @if($d->statut === 'en_attente')
                                <span class="badge bg-warning text-dark">En attente</span>
                            @elseif($d->statut === 'en_cours')
                                <span class="badge bg-primary">En cours</span>
                            @else
                                <span class="badge bg-success">Terminée</span>
                            @endif

                            <span class="badge bg-light text-dark border">{{ ucfirst($d->type) }}</span>

                            @if($d->urgence === 'urgente')
                                <span class="badge bg-danger">
                                    <i class="bi bi-exclamation-triangle me-1"></i>Urgente
                                </span>
                            @endif
                        </div>

                        {{-- Ligne 2 : Titre panne --}}
                        <div class="fw-bold mb-2" style="font-size:1rem;">
                            {{ $d->description }}
                        </div>

                        {{-- Ligne 3 : Infos localisation --}}
                        <div class="d-flex flex-wrap gap-3 text-muted mb-1" style="font-size:.82rem;">
                            <span><i class="bi bi-person me-1"></i>{{ $d->etudiante->name ?? '-' }}</span>

                            @if($d->chambre)
    <span><i class="bi bi-door-closed me-1"></i>Chambre {{ $d->chambre->numero }}</span>
    <span><i class="bi bi-building me-1"></i>Bloc {{ $d->chambre->bloc }}</span>
    <span><i class="bi bi-layers me-1"></i>Étage {{ $d->chambre->etage }}</span>
@elseif($d->lieu_commun)
    <span><i class="bi bi-geo-alt me-1"></i>{{ $d->lieu_commun }}</span>
@endif

                            <span><i class="bi bi-calendar me-1"></i>{{ $d->date_signalement?->format('d/m/Y') }}</span>
                        </div>

                        {{-- Ligne 4 : Infos technicien / commentaire / clôture --}}
                        @if($d->statut === 'en_cours' || $d->statut === 'terminee')
                        <div class="d-flex flex-wrap gap-3 mb-1" style="font-size:.82rem;">
                            @if($d->technicien)
                                <span class="text-primary">
                                    <i class="bi bi-person-gear me-1"></i>{{ $d->technicien->name }}
                                </span>
                            @endif
                            @if($d->commentaire_technicien)
                                <span class="text-warning">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $d->commentaire_technicien }}
                                </span>
                            @endif
                            @if($d->statut === 'terminee')
                                <span class="text-success">
                                    <i class="bi bi-check-circle me-1"></i>Clôturée le {{ $d->updated_at->format('d/m/Y') }}
                                </span>
                            @endif
                        </div>
                        @endif

                        {{-- Ligne 5 : Matériels --}}
                        @if($d->materiels->count())
                        <div class="d-flex flex-wrap gap-1 mt-1">
                            @foreach($d->materiels as $m)
                            <span class="badge bg-light text-dark border" style="font-size:.72rem;">
                                <i class="bi bi-box me-1"></i>{{ $m->nom_materiel }} ×{{ $m->quantite }}
                            </span>
                            @endforeach
                        </div>
                        @endif

                    </div>

                    {{-- Bouton action --}}
                    @if($d->statut === 'terminee')
                        <a href="{{ route('technicien.demandes.show', $d->id) }}"
                           class="btn btn-sm btn-outline-secondary align-self-start">
                            <i class="bi bi-eye me-1"></i>Voir
                        </a>
                    @else
                        <a href="{{ route('technicien.demandes.show', $d->id) }}"
                           class="btn btn-sm btn-primary align-self-start">
                            <i class="bi bi-wrench me-1"></i>Traiter
                        </a>
                    @endif

                </div>
            </div>
        </div>

    @empty
        <div class="card">
            <div class="card-body text-center text-muted py-5">
                <i class="bi bi-inbox fs-1 opacity-25"></i>
                <p class="mt-2 mb-0">Aucune demande pour ce filtre.</p>
            </div>
        </div>
    @endforelse
    </div>

</div>
@endsection