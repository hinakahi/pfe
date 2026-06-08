@extends('layouts.app')
@section('title', 'Mes Réservations')
@section('page-title', 'Mes Réservations')

@section('content')

{{-- Flèche retour --}}
<div class="mb-4">
    <a href="{{ route('etudiante.foyer.dashboard') }}"
       class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Retour au foyer
    </a>
</div>

{{-- Stats : 3 cartes seulement (sans Total) --}}
<div class="row mb-4">
    <div class="col-md-4">
        <div class="stat-card" style="background:linear-gradient(135deg,#fd7e14,#ffc107)">
            <div class="number">{{ $reservations->where('statut','en_attente')->count() }}</div>
            <div class="label"><i class="bi bi-hourglass-split me-1"></i>En attente</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card" style="background:linear-gradient(135deg,#198754,#20c997)">
            <div class="number">{{ $reservations->where('statut','validee')->count() }}</div>
            <div class="label"><i class="bi bi-check-circle me-1"></i>Validées</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card" style="background:linear-gradient(135deg,#dc3545,#e91e63)">
            <div class="number">{{ $reservations->where('statut','annulee')->count() }}</div>
            <div class="label"><i class="bi bi-x-circle me-1"></i>Annulées</div>
        </div>
    </div>
</div>

{{-- Barre recherche + Filtres --}}
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('etudiante.foyer.reservations') }}">
            <div class="row g-3 align-items-end">

                {{-- Recherche --}}
                <div class="col-md-5">
                    <label class="form-label text-muted small fw-semibold">
                        <i class="bi bi-search me-1"></i>Rechercher
                    </label>
                    <input type="text" name="search"
                           value="{{ request('search') }}"
                           class="form-control"
                           placeholder="Nom de l'article...">
                </div>

                {{-- Filtre statut --}}
                <div class="col-md-4">
                    <label class="form-label text-muted small fw-semibold">
                        <i class="bi bi-funnel me-1"></i>Statut
                    </label>
                    <select name="statut" class="form-select">
                        <option value="tous" {{ request('statut','tous') == 'tous' ? 'selected' : '' }}>
                            Tous
                        </option>
                        <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>
                            En attente
                        </option>
                        <option value="validee" {{ request('statut') == 'validee' ? 'selected' : '' }}>
                            Validées
                        </option>
                        <option value="annulee" {{ request('statut') == 'annulee' ? 'selected' : '' }}>
                            Annulées
                        </option>
                        <option value="refusee" {{ request('statut') == 'refusee' ? 'selected' : '' }}>
                            Refusées
                        </option>
                    </select>
                </div>

                {{-- Boutons --}}
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1">
                        <i class="bi bi-search me-1"></i>Filtrer
                    </button>
                    <a href="{{ route('etudiante.foyer.reservations') }}"
                       class="btn btn-outline-secondary">
                        <i class="bi bi-x"></i>
                    </a>
                </div>

            </div>
        </form>
    </div>
</div>

{{-- Contenu principal --}}
<div class="row">

    {{-- Tableau réservations --}}
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title mb-3">
                    <i class="bi bi-calendar-check me-1 text-primary"></i>
                    Mes réservations
                    @if(request('search') || (request('statut') && request('statut') != 'tous'))
                        <span class="badge bg-primary ms-2">Filtrées</span>
                    @endif
                </h6>

                @if($reservations->isEmpty())
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-cart-x fs-1 d-block mb-2"></i>
                        Aucune réservation trouvée.
                        <div class="mt-3">
                            <a href="{{ route('etudiante.foyer.articles') }}"
                               class="btn btn-sm btn-primary">
                                <i class="bi bi-shop me-1"></i>Voir le catalogue
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
                                    <div class="d-flex align-items-center gap-2">
                                        @if($r->article && $r->article->photo)
                                            <img src="{{ asset('storage/' . $r->article->photo) }}"
                                                 style="width:35px; height:35px; 
                                                        object-fit:cover; border-radius:8px;">
                                        @else
                                            <div style="width:35px; height:35px; border-radius:8px;
                                                        background:linear-gradient(135deg,#1a3c5e,#2d6a9f);
                                                        display:flex; align-items:center; justify-content:center;">
                                                <i class="bi bi-box text-white" style="font-size:0.8rem;"></i>
                                            </div>
                                        @endif
                                        <span class="fw-semibold">
                                            {{ $r->article->nom_article ?? '-' }}
                                        </span>
                                    </div>
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
                                    @elseif($r->statut === 'panier')
                                        <span class="badge bg-info text-dark">
                                            <i class="bi bi-cart3 me-1"></i>Panier
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if(in_array($r->statut, ['en_attente', 'panier']))
                                    <form method="POST"
                                          action="{{ route('etudiante.foyer.annuler', $r->id) }}"
                                          onsubmit="return confirm('Annuler cette réservation ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-x-lg me-1"></i>Annuler
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

    {{-- Promotions --}}
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="card-title mb-3">
                    <i class="bi bi-tag me-1 text-warning"></i>Promotions en cours
                </h6>
                @forelse($promotions as $promo)
                <div class="mb-3 p-3 rounded"
                     style="background:linear-gradient(135deg,#fff8e1,#fff3cd); 
                            border-left:4px solid #ffc107;">
                    <div class="fw-semibold small">{{ $promo->titre }}</div>
                    <div class="text-muted" style="font-size:12px;">
                        {{ Str::limit($promo->contenu, 70) }}
                    </div>
                    <div class="text-muted mt-1" style="font-size:11px;">
                        <i class="bi bi-clock me-1"></i>
                        {{ $promo->created_at->diffForHumans() }}
                    </div>
                </div>
                @empty
                <div class="text-muted text-center py-4">
                    <i class="bi bi-tag fs-2 d-block mb-2"></i>
                    Aucune promotion en cours.
                </div>
                @endforelse
            </div>
        </div>
    </div>

</div>

@endsection

@section('styles')
<style>
.stat-card {
    border-radius: 12px;
    padding: 20px;
    color: white;
    margin-bottom: 10px;
}
.stat-card .number { font-size: 2rem; font-weight: 700; }
.stat-card .label { font-size: 0.85rem; opacity: 0.85; }
</style>
@endsection