@extends('layouts.app')
@section('title', 'Réclamation')
@section('page-title', 'Détail de la réclamation')

@section('content')

{{-- Bouton retour --}}
<a href="{{ route('admin.reclamations.index') }}"
   class="btn btn-outline-secondary mb-4" style="border-radius:8px;">
    <i class="bi bi-arrow-left me-1"></i> Retour
</a>

<div class="row g-4">

    {{-- Colonne gauche : détails --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm" style="border-radius:16px; overflow:hidden;">

            {{-- Header coloré --}}
            <div class="p-4 text-white"
                 style="background:linear-gradient(135deg,#1a3c5e,#2d6a9f);">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-white d-flex align-items-center justify-content-center"
                         style="width:50px;height:50px;color:#1a3c5e;font-weight:700;font-size:1.2rem;flex-shrink:0;">
                        {{ strtoupper(substr($reclamation->etudiante->name, 0, 1)) }}
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold">{{ $reclamation->etudiante->name }}</h5>
                        <small class="opacity-75">
                            <i class="bi bi-clock me-1"></i>
                            {{ $reclamation->date_reclamation->format('d/m/Y à H:i') }}
                        </small>
                    </div>
                    <div class="ms-auto">
                        @if($reclamation->statut === 'en_attente')
                            <span class="badge bg-warning text-dark px-3 py-2">🟡 En attente</span>
                        @elseif($reclamation->statut === 'en_cours')
                            <span class="badge bg-primary px-3 py-2">🔵 En cours</span>
                        @else
                            <span class="badge bg-success px-3 py-2">🟢 Résolue</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Corps --}}
            <div class="card-body p-4">
                <div class="mb-4">
                    <div class="text-muted small fw-semibold mb-1 text-uppercase" style="letter-spacing:0.05em;">
                        Sujet
                    </div>
                    <div class="fw-bold fs-5">{{ $reclamation->sujet }}</div>
                </div>

                <div class="mb-4">
                    <div class="text-muted small fw-semibold mb-2 text-uppercase" style="letter-spacing:0.05em;">
                        Message
                    </div>
                    <div class="p-3 rounded-3" style="background:#f8f9fa;border-left:4px solid #2d6a9f;line-height:1.8;">
                        {{ $reclamation->message }}
                    </div>
                </div>

                @if($reclamation->reponse)
                <div>
                    <div class="text-muted small fw-semibold mb-2 text-uppercase" style="letter-spacing:0.05em;">
                        Réponse de l'admin
                    </div>
                    <div class="p-3 rounded-3" style="background:#d1fae5;border-left:4px solid #10b981;line-height:1.8;">
                        {{ $reclamation->reponse }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Colonne droite : formulaire --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm" style="border-radius:16px;">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-4">
                    <i class="bi bi-pencil-square me-2"></i>Traiter la réclamation
                </h6>

                @if(session('success'))
                    <div class="alert alert-success rounded-3 py-2 small">
                        <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('admin.reclamations.update', $reclamation->id) }}"
                      method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Statut</label>
                        <select name="statut" class="form-select" style="border-radius:8px;">
                            <option value="en_attente" @selected($reclamation->statut === 'en_attente')>
                                🟡 En attente
                            </option>
                            <option value="en_cours" @selected($reclamation->statut === 'en_cours')>
                                🔵 En cours
                            </option>
                            <option value="resolue" @selected($reclamation->statut === 'resolue')>
                                🟢 Résolue
                            </option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold small">Réponse à l'étudiante</label>
                        <textarea name="reponse" class="form-control" rows="5"
                                  style="border-radius:8px;"
                                  placeholder="Écrivez votre réponse...">{{ $reclamation->reponse }}</textarea>
                    </div>

                    <button type="submit" class="btn w-100 text-white fw-semibold"
                            style="background:linear-gradient(135deg,#1a3c5e,#2d6a9f);
                                   border-radius:8px;padding:10px;">
                        <i class="bi bi-check-lg me-1"></i> Enregistrer
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection