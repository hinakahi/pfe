@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 700px;">
    {{-- Boutons d'action --}}
    <div class="d-flex gap-2 mb-4 flex-wrap">
        <a href="{{ route('etudiante.reclamations.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
        
        {{-- Bouton Modifier --}}
        <a href="{{ route('etudiante.reclamations.edit', $reclamation) }}" class="btn btn-primary">
            <i class="bi bi-pencil me-1"></i> Modifier
        </a>
        
        {{-- Bouton Supprimer --}}
        <form action="{{ route('etudiante.reclamations.destroy', $reclamation) }}" method="POST" style="display:inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette réclamation ?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="bi bi-trash me-1"></i> Supprimer
            </button>
        </form>
    </div>

    {{-- Réclamation --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <strong>{{ $reclamation->sujet }}</strong>
            @php
                $statuts = [
                    'en_attente' => ['bg' => 'warning',   'label' => '⏳ En attente'],
                    'en_cours'   => ['bg' => 'primary',   'label' => '🔵 En cours'],
                    'resolue'    => ['bg' => 'success',   'label' => '✅ Résolue'],
                    'fermee'     => ['bg' => 'secondary', 'label' => '🔒 Fermée'],
                ];
                $badge = $statuts[$reclamation->statut] ?? ['bg' => 'secondary', 'label' => $reclamation->statut];
            @endphp
            <span class="badge bg-{{ $badge['bg'] }}">{{ $badge['label'] }}</span>
        </div>
        <div class="card-body">
            <p class="text-muted small mb-3">
                <i class="bi bi-calendar me-1"></i>
                Envoyée le {{ $reclamation->date_reclamation->format('d/m/Y à H:i') }}
            </p>
            <div class="bg-light rounded p-3">
                {{ $reclamation->message }}
            </div>
        </div>
    </div>

    {{-- Réponse de l'admin --}}
    <div class="card shadow-sm">
        <div class="card-header">
            <i class="bi bi-reply me-1"></i> Réponse de l'administration
        </div>
        <div class="card-body">
            @if($reclamation->reponse)
                <div class="bg-success bg-opacity-10 border-start border-success border-3 rounded p-3">
                    {{ $reclamation->reponse }}
                </div>
                <p class="text-muted small mt-2 mb-0">
                    <i class="bi bi-check-circle-fill text-success me-1"></i>
                    Votre réclamation a été traitée.
                </p>
            @else
                <div class="text-center text-muted py-4">
                    <i class="bi bi-hourglass-split fs-3 d-block mb-2"></i>
                    Aucune réponse pour le moment.<br>
                    <small>L'administration traitera votre réclamation prochainement.</small>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection