@extends('layouts.app')
@section('title', 'Dashboard Admin')

@section('sidebar')
    @include('admin.partials._sidebar')
@endsection

@section('page-title', 'Tableau de bord')

@section('content')

@if($periodeActive)
<div class="alert alert-info d-flex flex-wrap align-items-center">
    <i class="bi bi-calendar-check me-2"></i>
    <span>Période active : <strong>{{ $periodeActive->libelle }}</strong>
    ({{ $periodeActive->type }}) — jusqu'au {{ $periodeActive->date_fin->format('d/m/Y') }}</span>
</div>
@endif

{{-- KPIs --}}
<div class="row g-3">
    <div class="col-6 col-md-3">
        <a href="{{ route('admin.utilisateurs.index') }}" class="text-decoration-none">
            <div class="stat-card" style="background: linear-gradient(135deg,#1a3c5e,#2d6a9f); cursor:pointer; transition: opacity .2s;"
                 onmouseover="this.style.opacity='.85'" onmouseout="this.style.opacity='1'">
                <div class="number">{{ $totalUtilisateurs }}</div>
                <div class="label"><i class="bi bi-people me-1"></i>Utilisateurs</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg,#198754,#20c997)">
            <div class="number">{{ $chambresDisponibles }}</div>
            <div class="label"><i class="bi bi-door-open me-1"></i>Chambres libres</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg,#fd7e14,#ffc107)">
            <div class="number">{{ $demandesRenouvellement + $demandesChangement }}</div>
            <div class="label"><i class="bi bi-hourglass-split me-1"></i>Demandes en attente</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg,#dc3545,#e91e63)">
            <div class="number">{{ $maintenancesEnCours }}</div>
            <div class="label"><i class="bi bi-tools me-1"></i>Maintenances en cours</div>
        </div>
    </div>
</div>

{{-- Tableau derniers utilisateurs --}}
<div class="card mt-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <h6 class="card-title mb-0">Derniers utilisateurs inscrits</h6>
            <a href="{{ route('admin.utilisateurs.index') }}" class="btn btn-sm btn-outline-primary">Voir tous</a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th style="min-width:140px;">Nom</th>
                        <th style="min-width:100px;">Matricule</th>
                        <th style="min-width:90px;">Rôle</th>
                        <th style="min-width:180px;">Email</th>
                        <th style="min-width:100px;">Inscrit le</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($derniersUtilisateurs as $u)
                <tr>
                    <td>{{ $u->name }}</td>
                    <td><code>{{ $u->matricule }}</code></td>
                    <td><span class="badge bg-secondary">{{ $u->role }}</span></td>
                    <td>{{ $u->email }}</td>
                    <td>{{ $u->created_at->format('d/m/Y') }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('styles')
<style>
@media (max-width: 767px) {
    .stat-card { padding: 12px; }
    .stat-card .number { font-size: 1.4rem; }
    .stat-card .label { font-size: 0.75rem; }
}
</style>
@endpush

@endsection