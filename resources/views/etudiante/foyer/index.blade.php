@extends('layouts.app')
@section('title', 'Catalogue Foyer')
@section('page-title', 'Catalogue Foyer')

@section('content')

@php
    $panier = \App\Models\Reservation::where('etudiante_id', auth()->id())
              ->where('statut','panier')
              ->with('article')
              ->get();
@endphp

{{-- 🎁 LES PROMOTIONS EN HAUT --}}
@if($promotions->count() > 0)
<div class="mb-5">
    <h5 class="mb-3" style="color: #dc3545;">
        <i class="bi bi-tag-fill"></i> Promotions en cours
    </h5>
    <div class="row g-3 mb-4">
        @foreach($promotions as $promo)
        <div class="col-md-4 col-lg-3">
            <div class="card border-danger h-100" style="border-left: 4px solid #dc3545; border-radius: 12px;">
                <div class="card-body">
                    <h6 class="card-title text-danger fw-bold">{{ $promo->titre }}</h6>
                    <p class="card-text small text-muted">{{ Str::limit($promo->contenu, 60) }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">Publié le {{ $promo->date_publication->format('d/m/Y') }}</small>
                        <span class="badge bg-danger">Valide</span>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <hr class="my-4">
</div>
@endif

{{-- Filtres catégories --}}
<div class="d-flex gap-2 mb-4 flex-wrap">
    <a href="{{ route('etudiante.foyer') }}"
       class="btn btn-sm {{ !request('categorie') ? 'btn-primary' : 'btn-outline-secondary' }}">
        <i class="bi bi-grid me-1"></i> Tous
    </a>
    @foreach($categories as $cat)
    <a href="{{ route('etudiante.foyer', ['categorie' => $cat]) }}"
       class="btn btn-sm {{ request('categorie') == $cat ? 'btn-primary' : 'btn-outline-secondary' }}">
        @if($cat == 'fastfood') <i class="bi bi-egg-fried me-1"></i>
        @elseif($cat == 'magasin') <i class="bi bi-bag me-1"></i>
        @elseif($cat == 'cafeteria') <i class="bi bi-cup-hot me-1"></i>
        @endif
        {{ ucfirst($cat) }}
    </a>
    @endforeach
</div>

{{-- Grille articles --}}
@if($articles->isEmpty())
    <div class="text-center text-muted py-5">
        <i class="bi bi-box-seam fs-1 d-block mb-3"></i>
        Aucun article disponible pour le moment.
    </div>
@else
<div class="row g-3">
    @foreach($articles as $article)
    <div class="col-md-3 col-sm-6">
        <div class="card h-100 position-relative" style="border-radius:12px; overflow:hidden;">

            {{-- Badge PROMO --}}
            @if($article->promo_active && $article->prix_promo)
            <div style="position:absolute; top:8px; left:8px; z-index:2;">
                <span class="badge" style="background:linear-gradient(135deg,#dc3545,#e91e63); font-size:0.72rem; padding:4px 8px;">
                    <i class="bi bi-tag-fill me-1"></i>PROMO
                    @if($article->promo_date_fin)
                        — jusqu'au {{ \Carbon\Carbon::parse($article->promo_date_fin)->format('d/m') }}
                    @endif
                </span>
            </div>
            @endif

            {{-- Photo --}}
            <div style="height:120px; overflow:hidden;">
                @if($article->photo)
                    <img src="{{ asset('storage/' . $article->photo) }}"
                         style="width:100%; height:120px; object-fit:cover;">
                @else
                    <div style="height:120px; background:linear-gradient(135deg,#1a3c5e,#2d6a9f);
                                display:flex; align-items:center; justify-content:center;">
                        @if($article->categorie == 'fastfood')
                            <i class="bi bi-egg-fried text-white" style="font-size:2.5rem;"></i>
                        @elseif($article->categorie == 'magasin')
                            <i class="bi bi-bag text-white" style="font-size:2.5rem;"></i>
                        @else
                            <i class="bi bi-cup-hot text-white" style="font-size:2.5rem;"></i>
                        @endif
                    </div>
                @endif
            </div>

            <div class="card-body p-2 d-flex flex-column">
                <div class="fw-bold small mb-1">{{ $article->nom_article }}</div>
                <span class="badge bg-secondary mb-2" style="font-size:0.65rem; width:fit-content;">
                    {{ ucfirst($article->categorie) }}
                </span>

                {{-- Prix --}}
                <div class="mb-2">
                    @if($article->promo_active && $article->prix_promo)
                        <div class="text-decoration-line-through text-muted" style="font-size:0.75rem;">
                            {{ number_format($article->prix, 2) }} DA
                        </div>
                        <div class="fw-bold" style="color:#dc3545; font-size:1rem;">
                            {{ number_format($article->prix_promo, 2) }} DA
                        </div>
                        @if($article->promo_remarque)
                        <div class="text-muted" style="font-size:0.7rem;">
                            <i class="bi bi-info-circle me-1"></i>{{ $article->promo_remarque }}
                        </div>
                        @endif
                    @else
                        <div class="fw-bold text-primary" style="font-size:1rem;">
                            {{ number_format($article->prix, 2) }} DA
                        </div>
                    @endif
                </div>

                {{-- Stock --}}
                <div class="d-flex gap-1 flex-wrap mb-2">
                    <span class="badge {{ $article->stock <= 5 ? 'bg-warning text-dark' : 'bg-success' }}" style="font-size:0.65rem;">
                        <i class="bi bi-box me-1"></i>Stock : {{ $article->stock }}
                    </span>
                    @if($article->date_peremption)
                    <span class="badge bg-light text-muted border" style="font-size:0.65rem;">
                        <i class="bi bi-calendar me-1"></i>{{ \Carbon\Carbon::parse($article->date_peremption)->format('d/m/Y') }}
                    </span>
                    @endif
                </div>

                {{-- Bouton ajouter au panier --}}
                <form method="POST"
                      action="{{ route('etudiante.foyer.reserver', $article->id) }}"
                      class="mt-auto d-flex gap-1">
                    @csrf
                    <input type="number" name="quantite" value="1" min="1"
                           max="{{ $article->stock }}"
                           class="form-control form-control-sm"
                           style="width:55px; font-size:0.8rem;">
                    <button type="submit" class="btn btn-primary btn-sm flex-grow-1" style="font-size:0.8rem;">
                        <i class="bi bi-cart-plus me-1"></i>Ajouter
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

{{-- Bouton panier flottant --}}
<div style="position:fixed; bottom:30px; right:30px; z-index:1000;">
    <button onclick="document.getElementById('panierModal').style.display='flex'"
            class="btn btn-primary rounded-circle shadow-lg"
            style="width:60px; height:60px; font-size:1.4rem; position:relative;">
        <i class="bi bi-cart3"></i>
        @if($panier->count() > 0)
        <span id="panier-badge" style="position:absolute; top:-5px; right:-5px; background:#dc3545;
                     color:white; border-radius:50%; width:22px; height:22px;
                     font-size:0.7rem; display:flex; align-items:center; justify-content:center; font-weight:700;">
            {{ $panier->count() }}
        </span>
        @endif
    </button>
</div>

{{-- Modal Panier --}}
<div id="panierModal"
     style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5);
            z-index:2000; align-items:center; justify-content:center;">
    <div style="background:white; width:420px; max-height:85vh; border-radius:20px;
                overflow:hidden; display:flex; flex-direction:column; box-shadow:0 20px 60px rgba(0,0,0,0.3);">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center p-4"
             style="border-bottom:1px solid #f0f0f0;">
            <h6 class="mb-0 fw-bold fs-5">
                <i class="bi bi-cart3 me-2"></i>Mon panier
            </h6>
            <button onclick="document.getElementById('panierModal').style.display='none'"
                    style="background:none; border:none; font-size:1.2rem; cursor:pointer; color:#666;">
                ✕
            </button>
        </div>

        {{-- Liste --}}
        <div style="overflow-y:auto; flex:1; padding:1rem 1.5rem;" id="panier-liste">
            @if($panier->isEmpty())
                <div class="text-center text-muted py-4">
                    <i class="bi bi-cart-x fs-1 d-block mb-2"></i>
                    Votre panier est vide.
                </div>
            @else
                @foreach($panier as $r)
                @php
                    $prix = ($r->article->promo_active && $r->article->prix_promo)
                            ? $r->article->prix_promo : $r->article->prix;
                    $sousTotal = $r->quantite * $prix;
                @endphp
                <div class="d-flex justify-content-between align-items-center py-3 item-panier"
                     style="border-bottom:1px solid #f5f5f5;">
                    <div>
                        <div class="fw-semibold" style="font-size:0.95rem;">{{ $r->article->nom_article }}</div>
                        <div class="text-muted" style="font-size:0.82rem;">
                            {{ number_format($prix, 0) }} DA × {{ $r->quantite }} =
                            <strong>{{ number_format($sousTotal, 0) }} DA</strong>
                        </div>
                    </div>
                    <form method="POST"
                          action="{{ route('etudiante.foyer.annuler', $r->id) }}"
                          class="form-annuler">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                style="background:none; border:none; color:#dc3545; cursor:pointer; font-size:1rem;">
                            ✕
                        </button>
                    </form>
                </div>
                @endforeach

                {{-- Total --}}
                @php
                    $total = $panier->sum(function($r) {
                        $prix = ($r->article->promo_active && $r->article->prix_promo)
                                ? $r->article->prix_promo : $r->article->prix;
                        return $r->quantite * $prix;
                    });
                @endphp
                <div class="d-flex justify-content-between align-items-center pt-3 pb-2">
                    <span class="fw-bold fs-6">Total</span>
                    <span class="fw-bold fs-6" id="panier-total">{{ number_format($total, 0) }} DA</span>
                </div>
            @endif
        </div>

        {{-- Boutons --}}
        <div id="panier-footer" style="border-top:1px solid #f0f0f0; {{ $panier->isEmpty() ? 'display:none;' : '' }}">
            <div class="d-flex gap-2 p-4">
                <form method="POST" action="{{ route('etudiante.foyer.confirmer') }}" class="flex-grow-1">
                    @csrf
                    <button type="submit" class="btn w-100 text-white fw-semibold"
                            style="background:linear-gradient(135deg,#38b6ff,#1a8fd1); border-radius:12px; padding:12px;">
                        <i class="bi bi-check2-square me-1"></i> Confirmer la réservation
                    </button>
                </form>
                <button onclick="document.getElementById('panierModal').style.display='none'"
                        class="btn btn-light fw-semibold"
                        style="border-radius:12px; padding:12px 20px; border:1px solid #e0e0e0;">
                    Continuer
                </button>
            </div>
        </div>

    </div>
</div>

@endsection

@section('scripts')
<script>
document.querySelectorAll('.form-annuler').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        fetch(this.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({_method: 'DELETE'})
        })
        .then(response => {
            if (response.ok) {
                // Supprimer la ligne
                this.closest('.item-panier').remove();

                // Mettre à jour le badge
                const items = document.querySelectorAll('.item-panier');
                const badge = document.getElementById('panier-badge');
                if (badge) badge.textContent = items.length;

                // Si panier vide
                if (items.length === 0) {
                    document.getElementById('panier-liste').innerHTML = `
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-cart-x" style="font-size:2rem;display:block;margin-bottom:8px;"></i>
                            Votre panier est vide.
                        </div>`;
                    document.getElementById('panier-footer').style.display = 'none';
                    if (badge) badge.style.display = 'none';
                }
            }
        });
    });
});
</script>
@endsection