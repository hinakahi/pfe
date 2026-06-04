
@extends('layouts.app')
@section('title', 'Dashboard Admin')

@section('sidebar')
    @include('admin.partials._sidebar')
@endsection

@section('page-title', 'Tableau de bord')

@section('content')

@if($periodeActive)
<div class="alert alert-info">
    <i class="bi bi-calendar-check me-2"></i>
    Période active : <strong>{{ $periodeActive->libelle }}</strong>
    ({{ $periodeActive->type }}) — jusqu'au {{ $periodeActive->date_fin->format('d/m/Y') }}
</div>
@endif

{{-- KPIs --}}
<div class="row">
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg,#1a3c5e,#2d6a9f)">
            <div class="number">{{ $totalUtilisateurs }}</div>
            <div class="label"><i class="bi bi-people me-1"></i>Utilisateurs</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg,#198754,#20c997)">
            <div class="number">{{ $chambresDisponibles }}</div>
            <div class="label"><i class="bi bi-door-open me-1"></i>Chambres libres</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg,#fd7e14,#ffc107)">
            <div class="number">{{ $demandesRenouvellement + $demandesChangement }}</div>
            <div class="label"><i class="bi bi-hourglass-split me-1"></i>Demandes en attente</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg,#dc3545,#e91e63)">
            <div class="number">{{ $maintenancesEnCours }}</div>
            <div class="label"><i class="bi bi-tools me-1"></i>Maintenances en cours</div>
        </div>
    </div>
</div>

{{-- Tableau derniers utilisateurs --}}
<div class="card mt-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="card-title mb-0">Derniers utilisateurs inscrits</h6>
            <a href="{{ route('admin.utilisateurs.index') }}" class="btn btn-sm btn-outline-primary">Voir tous</a>
        </div>
        <table class="table table-hover">
            <thead><tr><th>Nom</th><th>Matricule</th><th>Rôle</th><th>Email</th><th>Inscrit le</th></tr></thead>
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
@endsection