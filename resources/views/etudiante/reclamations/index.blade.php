@extends('layouts.app')

@section('content')

{{-- Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1 fw-bold">Mes Réclamations</h4>
        <p class="mb-0 small" style="opacity:0.6;">Suivez vos réclamations et les réponses de l'administration</p>
    </div>
    <a href="{{ route('etudiante.reclamations.create') }}"
       class="btn text-white px-4"
       style="background:linear-gradient(135deg,#1a3c5e,#2d6a9f);border-radius:8px;">
        <i class="bi bi-plus-circle me-1"></i> Nouvelle réclamation
    </a>
</div>


{{-- Filtres --}}
<div class="card border-0 shadow-sm mb-4" style="border-radius:12px; background:rgba(26,60,94,0.04);">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('etudiante.reclamations.index') }}" class="d-flex gap-3 flex-wrap align-items-end">
            
            {{-- Filtre par statut --}}
            <div class="flex-grow-1" style="min-width: 200px;">
                <label class="form-label fw-semibold small mb-2">
                    <i class="bi bi-funnel me-1"></i> Statut
                </label>
                <select name="statut" class="form-select form-select-sm" onchange="this.form.submit()" style="border-radius:8px;border:0.5px solid #ddd;">
                    <option value="">Tous les statuts</option>
                    <option value="en_attente" {{ request('statut') === 'en_attente' ? 'selected' : '' }}> En attente</option>
                    <option value="en_cours" {{ request('statut') === 'en_cours' ? 'selected' : '' }}> En cours</option>
                    <option value="traitee" {{ request('statut') === 'traitee' ? 'selected' : '' }}> Traitée</option>
                    
                </select>
            </div>

            {{-- Filtre par date --}}
            <div class="flex-grow-1" style="min-width: 200px;">
                <label class="form-label fw-semibold small mb-2">
                    <i class="bi bi-calendar me-1"></i> Période
                </label>
                <select name="periode" class="form-select form-select-sm" onchange="this.form.submit()" style="border-radius:8px;border:0.5px solid #ddd;">
                    <option value="">Toutes les périodes</option>
                    <option value="7" {{ request('periode') === '7' ? 'selected' : '' }}>Derniers 7 jours</option>
                    <option value="30" {{ request('periode') === '30' ? 'selected' : '' }}>Dernier mois</option>
                    <option value="90" {{ request('periode') === '90' ? 'selected' : '' }}>Derniers 3 mois</option>
                    <option value="365" {{ request('periode') === '365' ? 'selected' : '' }}>Dernière année</option>
                </select>
            </div>

            {{-- Filtre par réponse --}}
            <div class="flex-grow-1" style="min-width: 200px;">
                <label class="form-label fw-semibold small mb-2">
                    <i class="bi bi-reply me-1"></i> Réponse
                </label>
                <select name="reponse" class="form-select form-select-sm" onchange="this.form.submit()" style="border-radius:8px;border:0.5px solid #ddd;">
                    <option value="">Tous</option>
                    <option value="avec" {{ request('reponse') === 'avec' ? 'selected' : '' }}>Avec réponse</option>
                    <option value="sans" {{ request('reponse') === 'sans' ? 'selected' : '' }}>Sans réponse</option>
                </select>
            </div>

            {{-- Bouton réinitialiser --}}
            @if(request()->filled('statut') || request()->filled('periode') || request()->filled('reponse'))
                <a href="{{ route('etudiante.reclamations.index') }}" class="btn btn-outline-secondary btn-sm" style="border-radius:8px;">
                    <i class="bi bi-arrow-clockwise me-1"></i> Réinitialiser
                </a>
            @endif

        </form>
    </div>
</div>

{{-- Informations de résultats --}}
@if($reclamations->total() > 0)
    <div class="mb-3" style="font-size:0.9rem;color:#666;">
        <i class="bi bi-info-circle me-1"></i>
        <strong>{{ $reclamations->total() }}</strong> réclamation(s) trouvée(s)
        @if($reclamations->currentPage() > 1)
            - Page <strong>{{ $reclamations->currentPage() }}</strong> sur <strong>{{ $reclamations->lastPage() }}</strong>
        @endif
    </div>
@endif

{{-- Liste cartes --}}
@forelse($reclamations as $r)
@php
    $statuts = [
        'en_attente' => ['bg' => '#ffc107', 'text' => '#000', 'label' => ' En attente'],
        'en_cours'   => ['bg' => '#0d6efd', 'text' => '#fff', 'label' => ' En cours'],
        'traitee'    => ['bg' => '#198754', 'text' => '#fff', 'label' => 'Traitée'],
        
    ];
    $badge = $statuts[$r->statut] ?? ['bg' => '#6c757d', 'text' => '#fff', 'label' => $r->statut];
@endphp

<div class="card border-0 shadow-sm mb-3 transition-all"
     style="border-radius:14px; border-left: 4px solid {{ $badge['bg'] }} !important; overflow:hidden; transition: box-shadow 0.2s;">
    <div class="card-body py-3 px-4">
        <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">

            {{-- Icône + contenu --}}
            <div class="d-flex align-items-center gap-3 flex-grow-1 overflow-hidden" style="min-width: 0;">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                     style="width:44px;height:44px;
                            background:linear-gradient(135deg,#1a3c5e,#2d6a9f);
                            color:#fff;font-size:1.1rem;">
                    <i class="bi bi-exclamation-circle"></i>
                </div>
                <div class="overflow-hidden" style="flex: 1;">
                    <div class="fw-bold text-truncate">{{ $r->sujet }}</div>
                    <div class="small mt-1" style="opacity:0.6;">
                        <i class="bi bi-calendar me-1"></i>
                        {{ $r->date_reclamation->format('d/m/Y') }}
                        @if($r->reponse)
                            &nbsp;·&nbsp;
                            <span class="text-success fw-semibold">
                                <i class="bi bi-check-circle-fill"></i> Répondu
                            </span>
                        @else
                            &nbsp;·&nbsp;
                            <span style="opacity:0.6;">
                                <i class="bi bi-hourglass-split"></i> Pas encore répondu
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Badge + bouton --}}
            <div class="d-flex align-items-center gap-2 flex-shrink-0">
                <span class="badge px-3 py-2"
                      style="background:{{ $badge['bg'] }};color:{{ $badge['text'] }};border-radius:20px;font-size:0.8rem;white-space:nowrap;">
                    {{ $badge['label'] }}
                </span>
                <a href="{{ route('etudiante.reclamations.show', $r) }}"
                   class="btn btn-sm px-3"
                   style="background:linear-gradient(135deg,#1a3c5e,#2d6a9f);color:#fff;border-radius:8px;white-space:nowrap;">
                    <i class="bi bi-eye me-1"></i> Voir
                </a>
            </div>

        </div>
    </div>
</div>

@empty
<div class="text-center py-5">
    <div style="font-size:4rem;">📭</div>
    <h5 class="mt-3 fw-bold">Aucune réclamation trouvée</h5>
    <p style="opacity:0.6;">
        @if(request()->filled('statut') || request()->filled('periode') || request()->filled('reponse'))
            Aucune réclamation ne correspond à vos filtres.
        @else
            Vous n'avez soumis aucune réclamation pour le moment.
        @endif
    </p>
    <a href="{{ route('etudiante.reclamations.create') }}"
       class="btn text-white px-4 mt-2"
       style="background:linear-gradient(135deg,#1a3c5e,#2d6a9f);border-radius:8px;">
        <i class="bi bi-plus-circle me-1"></i> Soumettre une réclamation
    </a>
</div>
@endforelse

{{-- Pagination personnalisée --}}
@if($reclamations->total() > 0)
<nav aria-label="Pagination" class="mt-4">
    <ul class="pagination justify-content-center gap-2">
        
        {{-- Lien précédent --}}
        <li class="page-item {{ $reclamations->onFirstPage() ? 'disabled' : '' }}">
            <a class="page-link" href="{{ $reclamations->previousPageUrl() }}" style="border-radius:8px;">
                <i class="bi bi-chevron-left me-1"></i> Précédent
            </a>
        </li>

        {{-- Numéros de page --}}
        @foreach ($reclamations->getUrlRange(max(1, $reclamations->currentPage() - 2), min($reclamations->lastPage(), $reclamations->currentPage() + 2)) as $page => $url)
            @if($page == $reclamations->currentPage())
                <li class="page-item active">
                    <span class="page-link" style="background:linear-gradient(135deg,#1a3c5e,#2d6a9f);border:none;border-radius:8px;">
                        {{ $page }}
                    </span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $url }}{{ request()->getQueryString() ? '&' : '?' }}page={{ $page }}" style="border-radius:8px;">
                        {{ $page }}
                    </a>
                </li>
            @endif
        @endforeach

        {{-- Lien suivant --}}
        <li class="page-item {{ !$reclamations->hasMorePages() ? 'disabled' : '' }}">
            <a class="page-link" href="{{ $reclamations->nextPageUrl() }}" style="border-radius:8px;">
                Suivant <i class="bi bi-chevron-right ms-1"></i>
            </a>
        </li>

    </ul>
</nav>
@endif

@endsection