@extends('layouts.app')

@section('page-title', 'Demandes de maintenance')

@section('content')
<div class="container-fluid">

    {{-- Filtres --}}
<div class="d-flex flex-wrap gap-2 mb-4">

    {{-- Dropdown Statut --}}
    <div class="dropdown">
        <button class="btn btn-sm btn-outline-secondary rounded-pill dropdown-toggle" type="button" data-bs-toggle="dropdown">
           
            @php
                $statutLabels = ['tous'=>'Tous les statuts','en_attente'=>'En attente','en_cours'=>'En cours','terminee'=>'Terminées'];
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

    {{-- Dropdown Type --}}
    <div class="dropdown">
        <button class="btn btn-sm btn-outline-secondary rounded-pill dropdown-toggle" type="button" data-bs-toggle="dropdown">
            
            @php
                $typeLabels = ['tous'=>'Tous les types','electricite'=>'Électricité','plomberie'=>'Plomberie','menuiserie'=>'Menuiserie','climatisation'=>'Climatisation','autre'=>'Autre'];
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
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-wrap align-items-start justify-content-between gap-3">

                    <div style="flex:1;min-width:240px;">
                        {{-- Badges --}}
                        <div class="d-flex flex-wrap gap-2 mb-2">
                            <span class="text-muted" style="font-size:.75rem;">#{{ $d->id }}</span>

                            @if($d->statut === 'en_attente')
                                <span class="badge bg-warning text-dark">En attente</span>
                            @elseif($d->statut === 'en_cours')
                                <span class="badge bg-primary">En cours</span>
                            @else
                                <span class="badge bg-success">Terminée</span>
                            @endif

                            @if($d->urgence === 'urgente')
                                <span class="badge bg-danger">
                                    <i class="bi bi-exclamation-triangle me-1"></i>Urgente
                                </span>
                            @endif

                            <span class="badge bg-light text-dark border">{{ ucfirst($d->type) }}</span>
                        </div>

                        {{-- Description --}}
                        <div class="fw-semibold mb-1">{{ $d->description }}</div>

                        {{-- Infos --}}
                        <div class="d-flex flex-wrap gap-3 text-muted" style="font-size:.82rem;">
                            <span><i class="bi bi-person me-1"></i>{{ $d->etudiante->name ?? '-' }}</span>
                            <span><i class="bi bi-door-closed me-1"></i>Chambre {{ $d->chambre->numero ?? '-' }}</span>
                            <span><i class="bi bi-calendar me-1"></i>{{ $d->date_signalement?->format('d/m/Y') }}</span>
                            @if($d->statut === 'terminee')
                                <span class="text-success">
                                    <i class="bi bi-check-circle me-1"></i>Clôturée {{ $d->updated_at->format('d/m/Y') }}
                                </span>
                            @endif
                        </div>

                        {{-- Matériels utilisés --}}
                        @if($d->materiels->count())
                        <div class="mt-2 d-flex flex-wrap gap-1">
                            @foreach($d->materiels as $m)
                            <span class="badge bg-light text-dark border" style="font-size:.72rem;">
                                <i class="bi bi-box me-1"></i>{{ $m->nom_materiel }} ×{{ $m->quantite }}
                            </span>
                            @endforeach
                        </div>
                        @endif
                    </div>

                    {{-- Bouton --}}
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
                <p class="mt-2">Aucune demande pour ce filtre.</p>
            </div>
        </div>
    @endforelse
    </div>

</div>
@endsection