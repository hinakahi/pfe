@extends('layouts.app')

@section('page-title', 'Gestion du stock')

@section('content')
<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="mb-0">Matériels en stock</h5>
            <small class="text-muted">{{ $stocks->count() }} matériel(s) enregistré(s)</small>
        </div>
        <a href="{{ route('technicien.stock.create') }}" class="btn btn-primary rounded-pill">
            <i class="bi bi-plus-lg me-1"></i>Ajouter un matériel
        </a>
    </div>

    {{-- Alertes stock faible --}}
    @if($stocks->filter(fn($s) => $s->est_faible || $s->est_epuise)->count())
    <div class="alert alert-warning mb-4">
        <i class="bi bi-exclamation-triangle me-2"></i>
        <strong>Attention !</strong>
        {{ $stocks->filter(fn($s) => $s->est_faible || $s->est_epuise)->count() }}
        matériel(s) sous le seuil minimum ou épuisé(s).
    </div>
    @endif

    {{-- Liste --}}
    <div class="row g-3">
    @forelse($stocks as $stock)
        <div class="col-md-6 col-xl-4">
            <div class="card h-100 {{ $stock->est_epuise ? 'border-danger' : ($stock->est_faible ? 'border-warning' : '') }}">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h6 class="fw-bold mb-0">{{ $stock->designation }}</h6>
                            <small class="text-muted">{{ ucfirst($stock->categorie) }}</small>
                        </div>
                        @if($stock->est_epuise)
                            <span class="badge bg-danger">Épuisé</span>
                        @elseif($stock->est_faible)
                            <span class="badge bg-warning text-dark">Stock faible</span>
                        @else
                            <span class="badge bg-success">Disponible</span>
                        @endif
                    </div>

                    <div class="d-flex align-items-end gap-1 my-3">
                        <span style="font-size:2rem;font-weight:800;line-height:1;
                            color:{{ $stock->est_epuise ? '#dc2626' : ($stock->est_faible ? '#d97706' : '#16a34a') }}">
                            {{ $stock->quantite }}
                        </span>
                        <span class="text-muted mb-1">{{ $stock->unite }}</span>
                    </div>

                    <div class="text-muted mb-3" style="font-size:.8rem;">
                        <i class="bi bi-arrow-down-circle me-1"></i>Seuil minimum : {{ $stock->seuil_minimum }} {{ $stock->unite }}
                    </div>

                    @if($stock->description)
                    <p class="text-muted" style="font-size:.8rem;">{{ $stock->description }}</p>
                    @endif

                    <div class="d-flex gap-2 mt-auto">
                        <a href="{{ route('technicien.stock.edit', $stock->id) }}"
                           class="btn btn-sm btn-outline-primary w-100">
                            <i class="bi bi-pencil me-1"></i>Modifier
                        </a>
                        <form method="POST" action="{{ route('technicien.stock.destroy', $stock->id) }}"
                              onsubmit="return confirm('Supprimer ce matériel ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center text-muted py-5">
                    <i class="bi bi-box-seam fs-1 opacity-25"></i>
                    <p class="mt-2">Aucun matériel en stock.</p>
                    <a href="{{ route('technicien.stock.create') }}" class="btn btn-primary rounded-pill">
                        <i class="bi bi-plus-lg me-1"></i>Ajouter un matériel
                    </a>
                </div>
            </div>
        </div>
    @endforelse
    </div>

</div>
@endsection