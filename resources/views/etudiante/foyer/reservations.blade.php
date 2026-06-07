@extends('layouts.app')
@section('title', 'Mes Réservations')
@section('page-title', 'Mes Réservations')

@section('content')

<div class="row">
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg,#1a3c5e,#2d6a9f)">
            <div class="number">{{ $reservations->count() }}</div>
            <div class="label"><i class="bi bi-list-check me-1"></i>Total réservations</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg,#fd7e14,#ffc107)">
            <div class="number">{{ $reservations->where('statut','en_attente')->count() }}</div>
            <div class="label"><i class="bi bi-hourglass-split me-1"></i>En attente</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg,#198754,#20c997)">
            <div class="number">{{ $reservations->where('statut','validee')->count() }}</div>
            <div class="label"><i class="bi bi-check-circle me-1"></i>Validées</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg,#dc3545,#e91e63)">
            <div class="number">{{ $reservations->where('statut','annulee')->count() }}</div>
            <div class="label"><i class="bi bi-x-circle me-1"></i>Annulées</div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="card-title mb-3">
                    <i class="bi bi-tag me-1 text-warning"></i> Promotions en cours
                </h6>
                @forelse($promotions as $promo)
                <div class="mb-3 p-2 rounded" style="background: linear-gradient(135deg,#fff8e1,#fff3cd); border-left: 4px solid #ffc107;">
                    <div class="fw-semibold small">{{ $promo->titre }}</div>
                    <div class="text-muted" style="font-size:12px">{{ Str::limit($promo->contenu, 60) }}</div>
                    <div class="text-muted" style="font-size:11px">
                        <i class="bi bi-clock me-1"></i>{{ $promo->created_at->diffForHumans() }}
                    </div>
                </div>
                @empty
                <div class="text-muted text-center py-3">
                    <i class="bi bi-tag fs-3 d-block mb-2"></i>
                    Aucune promotion en cours.
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title mb-3">
                    <i class="bi bi-calendar-check me-1 text-primary"></i> Mes réservations
                </h6>
                @if($reservations->isEmpty())
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-cart-x fs-1 d-block mb-2"></i>
                        Aucune réservation pour le moment.
                        <div class="mt-2">
                            <a href="{{ route('etudiante.foyer') }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-shop me-1"></i> Voir le catalogue
                            </a>
                        </div>
                    </div>
                @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Article</th>
                                <th>Quantité</th>
                                <th>Date</th>
                                <th>Statut</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reservations as $r)
                            <tr>
                                <td>
                                    <i class="bi bi-box me-1 text-muted"></i>
                                    {{ $r->article->designation ?? '-' }}
                                </td>
                                <td>{{ $r->quantite }}</td>
                                <td>{{ $r->created_at->format('d/m/Y') }}</td>
                                <td>
                                    @if($r->statut === 'en_attente')
                                        <span class="badge bg-warning text-dark">
                                            <i class="bi bi-hourglass-split me-1"></i>En attente
                                        </span>
                                    @elseif($r->statut === 'validee')
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle me-1"></i>Validée
                                        </span>
                                    @elseif($r->statut === 'annulee')
                                        <span class="badge bg-danger">
                                            <i class="bi bi-x-circle me-1"></i>Annulée
                                        </span>
                                    @elseif($r->statut === 'refusee')
                                        <span class="badge bg-secondary">
                                            <i class="bi bi-slash-circle me-1"></i>Refusée
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($r->statut === 'en_attente')
                                    <form method="POST"
                                          action="{{ route('etudiante.foyer.annuler', $r->id) }}"
                                          onsubmit="return confirm('Annuler cette réservation ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-x-lg"></i> Annuler
                                        </button>
                                    </form>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection