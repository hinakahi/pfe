@extends('layouts.app')

@section('page-title')
    Demande #{{ $maintenance->id }}
@endsection

@section('content')
<div class="container-fluid">

    {{-- Retour --}}
    <a href="{{ route('technicien.demandes') }}" class="btn btn-sm btn-outline-secondary rounded-pill mb-4">
        <i class="bi bi-arrow-left me-1"></i>Retour aux demandes
    </a>

    {{-- En-tête demande --}}
    <div class="card mb-4">
        <div class="card-body">
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

            {{-- Photo si présente --}}
            @if($maintenance->photo)
            <div class="mb-3">
                <div class="text-muted mb-2" style="font-size:.75rem;font-weight:700;text-transform:uppercase;">
                    Photo
                </div>
                <a href="{{ asset('storage/' . $maintenance->photo) }}" target="_blank">
                    <img src="{{ asset('storage/' . $maintenance->photo) }}" alt="Photo de la demande"
                         style="max-height:220px;border-radius:.5rem;" class="border">
                </a>
            </div>
            @endif

            <div class="d-flex flex-wrap gap-3 text-muted" style="font-size:.85rem;">
                <span><i class="bi bi-person me-1"></i>{{ $maintenance->etudiante->name ?? '-' }}</span>
                <span><i class="bi bi-envelope me-1"></i>{{ $maintenance->etudiante->email ?? '-' }}</span>

                @if($maintenance->chambre)
                    <span><i class="bi bi-door-closed me-1"></i>Chambre {{ $maintenance->chambre->numero }}</span>
                    <span><i class="bi bi-building me-1"></i>Bloc {{ $maintenance->chambre->bloc }}</span>
                    <span><i class="bi bi-layers me-1"></i>Étage {{ $maintenance->chambre->etage }}</span>
                @elseif($maintenance->lieu_commun)
                    <span><i class="bi bi-geo-alt me-1"></i>{{ $maintenance->lieu_commun }}</span>
                @endif

                <span><i class="bi bi-calendar me-1"></i>{{ $maintenance->date_signalement?->format('d/m/Y') }}</span>
                <span><i class="bi bi-tools me-1"></i>{{ ucfirst($maintenance->type) }}</span>
            </div>

            {{-- Technicien --}}
            @if($maintenance->technicien)
            <div class="alert alert-primary py-2 mt-3 mb-0" style="font-size:.82rem;">
                <i class="bi bi-person-gear me-1"></i>
                <strong>Traité par :</strong> {{ $maintenance->technicien->name }}
                @if($maintenance->date_resolution)
                    &nbsp;·&nbsp;
                    <strong>Clôturée le :</strong> {{ $maintenance->date_resolution->format('d/m/Y') }}
                @endif
                @if($maintenance->date_resolution && $maintenance->updated_at->gt($maintenance->date_resolution))
                    &nbsp;·&nbsp;
                    <strong class="text-warning">Modifiée le :</strong> {{ $maintenance->updated_at->format('d/m/Y à H:i') }}
                @endif
            </div>
            @endif

            {{-- Matériels utilisés --}}
            @if($maintenance->materiels->count())
            <div class="mt-3 pt-3 border-top">
                <div class="text-muted mb-2" style="font-size:.75rem;font-weight:700;text-transform:uppercase;">
                    Matériel utilisé
                </div>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($maintenance->materiels as $m)
                    <span class="badge bg-light text-dark border" style="font-size:.78rem;">
                        <i class="bi bi-box me-1"></i>{{ $m->stock->designation ?? 'Matériel supprimé' }} ×{{ $m->quantite }}
                        @if($m->stock_epuise)
                            <span class="text-danger ms-1">⚠ épuisé</span>
                        @endif
                    </span>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </div>

</div>
@endsection