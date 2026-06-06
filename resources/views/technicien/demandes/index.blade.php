@extends('layouts.app')

@section('page-title', 'Demandes de maintenance')

@section('content')
<div class="container-fluid">

    {{-- Filtres --}}
    <div class="d-flex flex-wrap gap-2 mb-4">
        @foreach([
            'tous'       => ['label'=>'Toutes',     'class'=>'btn-primary'],
            'en_attente' => ['label'=>'En attente', 'class'=>'btn-warning'],
            'en_cours'   => ['label'=>'En cours',   'class'=>'btn-info'],
            'terminee'   => ['label'=>'Terminées',  'class'=>'btn-success'],
        ] as $val => $f)
        <a href="?statut={{ $val }}"
           class="btn btn-sm {{ request('statut', 'tous') === $val ? $f['class'] : 'btn-outline-secondary' }} rounded-pill">
            {{ $f['label'] }}
            <span class="ms-1 opacity-75">
                ({{ $val === 'tous'
                    ? $demandes->count()
                    : $demandes->where('statut', $val)->count() }})
            </span>
        </a>
        @endforeach
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
                    <a href="{{ route('technicien.demandes.show', $d->id) }}"
                       class="btn btn-sm btn-primary align-self-start">
                        <i class="bi bi-wrench me-1"></i>Traiter
                    </a>

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