
@extends('layouts.app')
@section('title', 'Foyer')
@section('page-title', 'Foyer')

@section('content')

{{-- BOUTON RETOUR --}}
<div class="mb-4">
    <a href="{{ route('etudiante.foyer.dashboard') }}"
       class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Retour au foyer
    </a>
</div>

{{-- 3 CARTES CLIQUABLES --}}
<div class="row g-4 mb-4">

    {{-- CARTE 1 : MES RÉSERVATIONS --}}
    <div class="col-md-4">
        <a href="{{ route('etudiante.foyer.reservations') }}" class="text-decoration-none">
            <div class="stat-card" style="background:linear-gradient(135deg,#7c3aed,#a855f7); cursor:pointer;">
                <div class="card-icon"><i class="bi bi-clipboard-check"></i></div>
                <div class="number">{{ $totalReservations }}</div>
                <div class="label">Mes Réservations</div>
                <div class="card-sub">Voir mon historique →</div>
            </div>
        </a>
    </div>

    {{-- CARTE 2 : LISTE DES ARTICLES --}}
    <div class="col-md-4">
        <a href="{{ route('etudiante.foyer.articles') }}" class="text-decoration-none">
            <div class="stat-card" style="background:linear-gradient(135deg,#38b6ff,#1a8fd1); cursor:pointer;">
                <div class="card-icon"><i class="bi bi-shop"></i></div>
                <div class="number">{{ $totalArticles }}</div>
                <div class="label">Liste des Articles</div>
                <div class="card-sub">Voir le catalogue →</div>
            </div>
        </a>
    </div>

    {{-- CARTE 3 : PROMOTIONS --}}
    <div class="col-md-4">
        <a href="{{ route('etudiante.foyer.promotions') }}" class="text-decoration-none">
            <div class="stat-card" style="background:linear-gradient(135deg,#dc3545,#e91e63); cursor:pointer;">
                <div class="card-icon"><i class="bi bi-tag-fill"></i></div>
                <div class="number">{{ $totalPromotions }}</div>
                <div class="label">Promotions</div>
                <div class="card-sub">Voir les offres →</div>
            </div>
        </a>
    </div>

</div>

{{-- FILTRES --}}
<div class="card border-0 shadow-sm mb-4" style="border-radius:14px;">
    <div class="card-body">
        <form method="GET" action="{{ route('etudiante.foyer.reservations') }}" class="row g-2 align-items-end">
            <div class="col-md-5">
                <label class="form-label text-muted small mb-1">
                    <i class="bi bi-search me-1"></i>Rechercher
                </label>
                <input type="text" name="search" class="form-control"
                       placeholder="Nom de l'article..." value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label text-muted small mb-1">
                    <i class="bi bi-funnel me-1"></i>Statut
                </label>
                <select name="statut" class="form-select">
                    <option value="tous">Tous</option>
                    <option value="en_attente" {{ request('statut')=='en_attente'?'selected':'' }}>En attente</option>
                    <option value="validee"    {{ request('statut')=='validee'   ?'selected':'' }}>Validée</option>
                    <option value="annulee"    {{ request('statut')=='annulee'   ?'selected':'' }}>Annulée</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill">
                    <i class="bi bi-search me-1"></i>Filtrer
                </button>
                <a href="{{ route('etudiante.foyer.reservations') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x"></i>
                </a>
            </div>
        </form>
    </div>
</div>

{{-- LISTE + PROMOTIONS --}}
<div class="row g-4">

    {{-- LISTE DES RÉSERVATIONS --}}
    <div class="col-md-8">
        <div class="card border-0 shadow-sm" style="border-radius:14px; overflow:hidden;">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                <h5 class="fw-bold mb-0">
                    <i class="bi bi-calendar2-check text-primary me-2"></i>Mes réservations
                </h5>
            </div>
            <div class="table-responsive px-4 pb-4">
                @if($reservations->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-inbox" style="font-size:3rem;display:block;margin-bottom:12px;"></i>
                    Aucune réservation trouvée.
                </div>
                @else
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Article</th>
                            <th class="text-center">Quantité</th>
                            <th class="text-center">Date</th>
                            <th class="text-center">Statut</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reservations as $r)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @if($r->article && $r->article->photo)
                                        <img src="{{ asset('storage/'.$r->article->photo) }}"
                                             style="width:40px;height:40px;object-fit:cover;border-radius:8px;">
                                    @else
                                        <div style="width:40px;height:40px;background:linear-gradient(135deg,#1a3c5e,#2d6a9f);
                                                    border-radius:8px;display:flex;align-items:center;justify-content:center;">
                                            <i class="bi bi-box text-white" style="font-size:0.9rem;"></i>
                                        </div>
                                    @endif
                                    <span class="fw-semibold">{{ $r->article->nom_article ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="text-center">{{ $r->quantite }}</td>
                            <td class="text-center text-muted small">{{ $r->created_at->format('d/m/Y') }}</td>
                            <td class="text-center">
                                @if($r->statut === 'en_attente')
                                    <span class="badge" style="background:#fd7e14;">
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
                                @elseif($r->statut === 'panier')
                                    <span class="badge bg-info text-dark">
                                        <i class="bi bi-cart3 me-1"></i>Panier
                                    </span>
                                @elseif($r->statut === 'refusee')
                                    <span class="badge bg-secondary">
                                        <i class="bi bi-slash-circle me-1"></i>Refusée
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if(in_array($r->statut, ['en_attente', 'panier']))
                                <form method="POST"
                                      action="{{ route('etudiante.foyer.annuler', $r->id) }}"
                                      onsubmit="return confirm('Annuler cette réservation ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-x me-1"></i>Annuler
                                    </button>
                                </form>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </div>

    {{-- PROMOTIONS EN COURS --}}
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100" style="border-radius:14px;">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                <h5 class="fw-bold mb-0">
                    <i class="bi bi-tag-fill text-danger me-2"></i>Promotions en cours
                </h5>
            </div>
            <div class="card-body px-4 pb-4">
                @forelse($promotions as $promo)
                <div class="mb-3 p-3"
                     style="border-left:3px solid #dc3545;border-radius:0 10px 10px 0;background:#fff5f5;">
                    <h6 class="fw-bold text-danger mb-1">
                        <i class="bi bi-fire me-1"></i>{{ $promo->titre }}
                    </h6>
                    <p class="small text-muted mb-2">{{ Str::limit($promo->contenu, 70) }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="bi bi-calendar me-1"></i>{{ $promo->created_at->format('d/m/Y') }}
                        </small>
                        <span class="badge bg-danger">Valide</span>
                    </div>
                    <a href="{{ route('etudiante.foyer.articles') }}"
                       class="btn btn-outline-danger btn-sm w-100 mt-2">
                        <i class="bi bi-shop me-1"></i>Profiter de l'offre
                    </a>
                </div>
                @empty
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-tag" style="font-size:2.5rem;display:block;margin-bottom:10px;"></i>
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
    border-radius: 16px;
    padding: 28px 24px;
    color: white;
    transition: transform 0.25s, box-shadow 0.25s;
}
.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 28px rgba(0,0,0,0.18) !important;
}
.stat-card .card-icon {
    font-size: 2.2rem;
    margin-bottom: 10px;
    opacity: 0.9;
}
.stat-card .number {
    font-size: 2.5rem;
    font-weight: 700;
    line-height: 1;
    margin-bottom: 8px;
}
.stat-card .label {
    font-size: 1rem;
    font-weight: 600;
    opacity: 0.95;
}
.stat-card .card-sub {
    font-size: 0.8rem;
    opacity: 0.7;
    margin-top: 6px;
}
.table th {
    font-size: 0.82rem;
    text-transform: uppercase;
    letter-spacing: .04em;
    color: #6c757d;
    font-weight: 600;
}
</style>
@endsection