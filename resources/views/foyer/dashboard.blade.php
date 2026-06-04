@extends('layouts.app')
@section('title', 'Dashboard Foyer')

@section('sidebar')
    @include('foyer.partials._sidebar')
@endsection

@section('page-title', 'Tableau de bord — Foyer')

@section('content')

{{-- KPIs --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg,#1a3c5e,#2d6a9f);">
            <div class="number">{{ $stats['total_articles'] }}</div>
            <div class="label"><i class="bi bi-box-seam me-1"></i>Total articles</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg,#198754,#20c997);">
            <div class="number">{{ $stats['articles_disponibles'] }}</div>
            <div class="label"><i class="bi bi-check-circle me-1"></i>Disponibles</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg,#fd7e14,#ffc107);">
            <div class="number">{{ $stats['reservations'] }}</div>
            <div class="label"><i class="bi bi-hourglass-split me-1"></i>Réservations en attente</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg,#dc3545,#e9868e);">
            <div class="number">{{ $stats['stock_faible'] }}</div>
            <div class="label"><i class="bi bi-exclamation-triangle me-1"></i>Stock faible (≤5)</div>
        </div>
    </div>
</div>

{{-- Derniers articles --}}
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="card-title mb-0">Derniers articles ajoutés</h6>
            <a href="{{ route('foyer.catalogue.index') }}" class="btn btn-sm btn-outline-primary">
                Voir tout
            </a>
        </div>
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Article</th>
                    <th>Prix (DA)</th>
                    <th>Stock</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                @forelse($articles as $article)
                <tr>
                    <td class="fw-semibold">{{ $article->nom_article }}</td>
                    <td>{{ number_format($article->prix, 2) }}</td>
                    <td>
                        <span class="{{ $article->stock <= 5 ? 'text-danger fw-bold' : '' }}">
                            {{ $article->stock }}
                        </span>
                    </td>
                    <td>
                        @if($article->disponible)
                            <span class="badge bg-success">Disponible</span>
                        @else
                            <span class="badge bg-secondary">Indisponible</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted py-3">Aucun article.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection