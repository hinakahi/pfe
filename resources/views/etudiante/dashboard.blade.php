@extends('layouts.app')

@section('page-title', 'Dashboard')

@section('sidebar')
    @include('etudiante.partials._sidebar')
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4>Bienvenue, {{ auth()->user()->prenom ?? auth()->user()->name }} ! 👋</h4>
                    <p class="text-muted">Tableau de bord de votre espace étudiant</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Dernière demande renouvellement -->
        @if($derniereDemandeRenouvellement)
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-arrow-counterclockwise"></i> Dernière demande renouvellement</h5>
                </div>
                <div class="card-body">
                    <p><strong>Statut :</strong> 
                        <span class="badge bg-{{ $derniereDemandeRenouvellement->statut === 'accepte' ? 'success' : ($derniereDemandeRenouvellement->statut === 'refuse' ? 'danger' : 'warning') }}">
                            {{ $derniereDemandeRenouvellement->statut }}
                        </span>
                    </p>
                    <p><strong>Date :</strong> {{ $derniereDemandeRenouvellement->created_at->format('d/m/Y') }}</p>
                    <a href="{{ route('etudiante.hebergement.renouvellement') }}" class="btn btn-sm btn-primary">
                        Voir détails →
                    </a>
                </div>
            </div>
        </div>
        @else
        <div class="col-md-6 mb-4">
            <div class="card border-warning">
                <div class="card-body text-center">
                    <i class="bi bi-info-circle" style="font-size: 2rem; color: #ffc107;"></i>
                    <p class="mt-2">Aucune demande renouvellement</p>
                    <a href="{{ route('etudiante.hebergement.renouvellement') }}" class="btn btn-sm btn-warning">
                        Faire une demande
                    </a>
                </div>
            </div>
        </div>
        @endif

        <!-- Dernière demande changement -->
        @if($derniereDemandeChangement)
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-arrow-left-right"></i> Dernière demande changement</h5>
                </div>
                <div class="card-body">
                    <p><strong>Statut :</strong> 
                        <span class="badge bg-{{ $derniereDemandeChangement->statut === 'accepte' ? 'success' : ($derniereDemandeChangement->statut === 'refuse' ? 'danger' : 'warning') }}">
                            {{ $derniereDemandeChangement->statut }}
                        </span>
                    </p>
                    <p><strong>Date :</strong> {{ $derniereDemandeChangement->created_at->format('d/m/Y') }}</p>
                    <a href="{{ route('etudiante.changement') }}" class="btn btn-sm btn-primary">
                        Voir détails →
                    </a>
                </div>
            </div>
        </div>
        @else
        <div class="col-md-6 mb-4">
            <div class="card border-info">
                <div class="card-body text-center">
                    <i class="bi bi-info-circle" style="font-size: 2rem; color: #0dcaf0;"></i>
                    <p class="mt-2">Aucune demande changement</p>
                    <a href="{{ route('etudiante.changement') }}" class="btn btn-sm btn-info">
                        Demander un changement
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> Actions rapides</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('etudiante.hebergement.renouvellement') }}" class="btn btn-outline-primary me-2 mb-2">
                        <i class="bi bi-arrow-counterclockwise"></i> Renouvellement
                    </a>
                    <a href="{{ route('etudiante.changement') }}" class="btn btn-outline-info me-2 mb-2">
                        <i class="bi bi-arrow-left-right"></i> Changement
                    </a>
                    <a href="{{ route('etudiante.foyer') }}" class="btn btn-outline-success me-2 mb-2">
                        <i class="bi bi-shop"></i> Foyer
                    </a>
                    <a href="{{ route('etudiante.maintenance.index') }}" class="btn btn-outline-warning me-2 mb-2">
                        <i class="bi bi-tools"></i> Maintenance
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection