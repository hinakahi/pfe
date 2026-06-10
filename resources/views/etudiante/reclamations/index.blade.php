@extends('layouts.app')

@section('content')

{{-- Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1 fw-bold"> Mes Réclamations</h4>
        <p class="mb-0 small" style="opacity:0.6;">Suivez vos réclamations et les réponses de l'administration</p>
    </div>
    <a href="{{ route('etudiante.reclamations.create') }}"
       class="btn text-white px-4"
       style="background:linear-gradient(135deg,#1a3c5e,#2d6a9f);border-radius:8px;">
        <i class="bi bi-plus-circle me-1"></i> Nouvelle réclamation
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 border-0 shadow-sm">
        <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Liste cartes --}}
@forelse($reclamations as $r)
@php
    $statuts = [
        'en_attente' => ['bg' => '#ffc107', 'text' => '#000', 'label' => '⏳ En attente'],
        'en_cours'   => ['bg' => '#0d6efd', 'text' => '#fff', 'label' => '🔵 En cours'],
        'traitee'    => ['bg' => '#198754', 'text' => '#fff', 'label' => '✅ Traitée'],
        'resolue'    => ['bg' => '#198754', 'text' => '#fff', 'label' => '✅ Résolue'],
        'fermee'     => ['bg' => '#6c757d', 'text' => '#fff', 'label' => '🔒 Fermée'],
    ];
    $badge = $statuts[$r->statut] ?? ['bg' => '#6c757d', 'text' => '#fff', 'label' => $r->statut];
@endphp

<div class="card border-0 shadow-sm mb-3"
     style="border-radius:14px; border-left: 4px solid {{ $badge['bg'] }} !important; overflow:hidden;">
    <div class="card-body py-3 px-4">
        <div class="d-flex align-items-center justify-content-between gap-3">

            {{-- Icône + contenu --}}
            <div class="d-flex align-items-center gap-3 flex-grow-1 overflow-hidden">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                     style="width:44px;height:44px;
                            background:linear-gradient(135deg,#1a3c5e,#2d6a9f);
                            color:#fff;font-size:1.1rem;">
                    <i class="bi bi-exclamation-circle"></i>
                </div>
                <div class="overflow-hidden">
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
                      style="background:{{ $badge['bg'] }};color:{{ $badge['text'] }};border-radius:20px;font-size:0.8rem;">
                    {{ $badge['label'] }}
                </span>
                <a href="{{ route('etudiante.reclamations.show', $r) }}"
                   class="btn btn-sm px-3"
                   style="background:linear-gradient(135deg,#1a3c5e,#2d6a9f);color:#fff;border-radius:8px;">
                    <i class="bi bi-eye me-1"></i> Voir
                </a>
            </div>

        </div>
    </div>
</div>

@empty
<div class="text-center py-5">
    <div style="font-size:4rem;">📭</div>
    <h5 class="mt-3 fw-bold">Aucune réclamation</h5>
    <p style="opacity:0.6;">Vous n'avez soumis aucune réclamation pour le moment.</p>
    <a href="{{ route('etudiante.reclamations.create') }}"
       class="btn text-white px-4 mt-2"
       style="background:linear-gradient(135deg,#1a3c5e,#2d6a9f);border-radius:8px;">
        <i class="bi bi-plus-circle me-1"></i> Soumettre une réclamation
    </a>
</div>
@endforelse

{{-- Pagination --}}
<div class="mt-3">{{ $reclamations->links() }}</div>

@endsection