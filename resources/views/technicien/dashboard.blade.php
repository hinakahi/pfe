@extends('layouts.app')

@section('page-title', 'Tableau de bord – Technicien')

@section('content')
<div class="container-fluid">

    {{-- Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="stat-card" style="background:linear-gradient(135deg,#f59e0b,#d97706);">
                <div class="number">{{ $stats['en_attente'] }}</div>
                <div class="label"><i class="bi bi-clock me-1"></i>En attente</div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="stat-card" style="background:linear-gradient(135deg,#3b82f6,#2563eb);">
                <div class="number">{{ $stats['en_cours'] }}</div>
                <div class="label"><i class="bi bi-tools me-1"></i>En cours</div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="stat-card" style="background:linear-gradient(135deg,#22c55e,#16a34a);">
                <div class="number">{{ $stats['terminees'] }}</div>
                <div class="label"><i class="bi bi-check-circle me-1"></i>Terminées</div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="stat-card" style="background:linear-gradient(135deg,#ef4444,#dc2626);">
                <div class="number">{{ $stats['urgentes'] }}</div>
                <div class="label"><i class="bi bi-exclamation-triangle me-1"></i>Urgentes</div>
            </div>
        </div>
    </div>

    {{-- Demandes actives --}}
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between py-3">
            <div>
                <h5 class="mb-0"><i class="bi bi-list-check me-2 text-primary"></i>Demandes actives</h5>
                <small class="text-muted">En attente et en cours d'intervention</small>
            </div>
            <a href="{{ route('technicien.demandes') }}" class="btn btn-sm btn-outline-primary rounded-pill">
                Voir tout
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Description</th>
                            <th>Étudiante</th>
                            <th>Chambre</th>
                            <th>Urgence</th>
                            <th>Statut</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($demandes as $d)
                        <tr>
                            <td class="text-muted" style="font-size:.8rem;">{{ $d->id }}</td>
                            <td style="max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                {{ $d->description }}
                            </td>
                            <td>{{ $d->etudiante->name ?? '-' }}</td>
                            <td>{{ $d->chambre->numero ?? '-' }}</td>
                            <td>
                                @if($d->urgence === 'urgente')
                                    <span class="badge bg-danger">Urgente</span>
                                @else
                                    <span class="badge bg-secondary">Normale</span>
                                @endif
                            </td>
                            <td>
                                @if($d->statut === 'en_attente')
                                    <span class="badge bg-warning text-dark">En attente</span>
                                @elseif($d->statut === 'en_cours')
                                    <span class="badge bg-primary">En cours</span>
                                @else
                                    <span class="badge bg-success">Terminée</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('technicien.demandes.show', $d->id) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-wrench"></i> Traiter
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bi bi-check-circle text-success me-2"></i>Aucune demande active.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection