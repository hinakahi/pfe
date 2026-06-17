@extends('layouts.app')
@section('page-title', 'Dashboard Hébergement')
@section('content')

{{-- Topbar titre --}}
<div class="mb-4">
    <h4 class="fw-bold mb-1">Tableau de bord — Hébergement</h4>
    <p class="text-muted mb-0">Vue d'ensemble de la gestion des chambres.</p>
</div>

{{-- Stats colorées --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="p-4 rounded-3 text-white" style="background: linear-gradient(135deg, #1a3c5e, #2d6a9f);">
            <div style="font-size:2rem; font-weight:700;">{{ $stats['total'] }}</div>
            <div><i class="bi bi-door-open me-1"></i> Total chambres</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="p-4 rounded-3 text-white" style="background: linear-gradient(135deg, #28a745, #20c997);">
            <div style="font-size:2rem; font-weight:700;">{{ $stats['disponibles'] }}</div>
            <div><i class="bi bi-check-circle me-1"></i> Disponibles</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="p-4 rounded-3 text-white" style="background: linear-gradient(135deg, #fd7e14, #ffc107);">
            <div style="font-size:2rem; font-weight:700;">{{ $stats['occupees'] }}</div>
            <div><i class="bi bi-person-fill me-1"></i> Occupées</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="p-4 rounded-3 text-white" style="background: linear-gradient(135deg, #dc3545, #e83e8c);">
            <div style="font-size:2rem; font-weight:700;">{{ $stats['publiees'] }}</div>
            <div><i class="bi bi-eye me-1"></i> Publiées</div>
        </div>
    </div>
</div>

{{-- Raccourcis --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <a href="{{ route('hebergement.chambres.index') }}" class="card text-decoration-none" style="border-left: 4px solid #2d6a9f;">
            <div class="card-body d-flex align-items-center gap-3">
                <i class="bi bi-door-open fs-2 text-primary"></i>
                <div>
                    <div class="fw-bold">Gérer les chambres</div>
                    <small class="text-muted">Ajouter, modifier, supprimer</small>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="{{ route('hebergement.renouvellements.index') }}" class="card text-decoration-none" style="border-left: 4px solid #28a745;">
            <div class="card-body d-flex align-items-center gap-3">
                <i class="bi bi-arrow-repeat fs-2 text-success"></i>
                <div>
                    <div class="fw-bold">Renouvellements</div>
                    <small class="text-muted">Valider ou refuser</small>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="{{ route('hebergement.changements.index') }}" class="card text-decoration-none" style="border-left: 4px solid #fd7e14;">
            <div class="card-body d-flex align-items-center gap-3">
                <i class="bi bi-shuffle fs-2 text-warning"></i>
                <div>
                    <div class="fw-bold">Changements</div>
                    <small class="text-muted">Accepter ou refuser</small>
                </div>
            </div>
        </a>
    </div>
</div>

{{-- Dernières chambres ajoutées --}}
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0">Dernières chambres ajoutées</h6>
            <a href="{{ route('hebergement.chambres.index') }}" class="btn btn-sm btn-outline-secondary">Voir tout</a>
        </div>
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Numéro</th>
                    <th>Type</th>
                    <th>Bloc</th>
                    <th>Étage</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dernieres as $chambre)
                <tr>
                    <td><strong>{{ $chambre->numero }}</strong></td>
                    <td>{{ ucfirst($chambre->type) }}</td>
                    <td>{{ $chambre->bloc ?? '-' }}</td>
                    <td>{{ $chambre->etage }}</td>
                    <td>
                       @if($chambre->statut === 'libre')
    <span class="badge bg-success">Disponible</span>
@elseif($chambre->statut === 'partielle')
    <span class="badge bg-warning text-dark">1 place libre</span>
@else
    <span class="badge bg-danger">Occupée</span>
@endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-3">Aucune chambre enregistrée.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection