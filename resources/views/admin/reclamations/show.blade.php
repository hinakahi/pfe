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
        <div class="card border-0" style="border-radius:12px; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,.06);">

            {{-- Header --}}
            <div class="p-4 d-flex align-items-center gap-3" style="background:#fff; border-bottom:1px solid #eee;">
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                     style="width:44px;height:44px;background:#e6f1fb;color:#0c447c;font-weight:600;font-size:1rem;flex-shrink:0;">
                    {{ strtoupper(substr($reclamation->etudiante->name, 0, 1)) }}
                </div>
                <div>
                    <h6 class="mb-0 fw-semibold" style="font-size:.95rem;">{{ $reclamation->etudiante->name }}</h6>
                    <small class="text-muted d-flex align-items-center gap-1" style="font-size:.75rem;">
                        <i class="bi bi-clock"></i>
                        {{ $reclamation->date_reclamation->format('d/m/Y à H:i') }}
                    </small>
                </div>

                <div class="ms-auto">
                    @if($reclamation->statut === 'en_attente')
                        <span class="d-inline-flex align-items-center gap-1 px-3 py-1"
                              style="background:#faeeda;color:#854f0b;border-radius:999px;font-size:.75rem;font-weight:600;">
                            <i class="bi bi-hourglass-split"></i> En attente
                        </span>
                    @elseif($reclamation->statut === 'en_cours')
                        <span class="d-inline-flex align-items-center gap-1 px-3 py-1"
                              style="background:#e6f1fb;color:#0c447c;border-radius:999px;font-size:.75rem;font-weight:600;">
                            <i class="bi bi-arrow-repeat"></i> En cours
                        </span>
                    @else
                        <span class="d-inline-flex align-items-center gap-1 px-3 py-1"
                              style="background:#eaf3de;color:#27500a;border-radius:999px;font-size:.75rem;font-weight:600;">
                            <i class="bi bi-check-circle"></i> Résolue
                        </span>
                    @endif
                </div>
            </div>

            {{-- Corps --}}
            <div class="card-body p-4 d-flex flex-column gap-4">
                <div>
                    <div class="text-muted mb-1" style="font-size:.7rem;letter-spacing:.05em;">SUJET</div>
                    <div class="fw-semibold" style="font-size:1rem;">{{ $reclamation->sujet }}</div>
                </div>

                <div>
                    <div class="text-muted mb-2" style="font-size:.7rem;letter-spacing:.05em;">MESSAGE DE L'ÉTUDIANTE</div>
                    <div class="p-3 rounded-3" style="background:#f7f7f5;font-size:.9rem;line-height:1.7;">
                        {{ $reclamation->message }}
                    </div>
                </div>

                @if($reclamation->reponse)
                <div>
                    <div class="mb-2 d-flex align-items-center gap-1" style="font-size:.7rem;letter-spacing:.05em;color:#27500a;">
                        <i class="bi bi-reply-fill"></i> RÉPONSE DE L'ADMIN
                    </div>
                    <div class="p-3 rounded-3" style="background:#eaf3de;font-size:.9rem;line-height:1.7;">
                        {{ $reclamation->reponse }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Colonne droite : formulaire --}}
    <div class="col-lg-4">
        <div class="card border-0" style="border-radius:12px; position:sticky; top:1rem; max-height:calc(100vh - 2rem); overflow-y:auto; box-shadow:0 1px 3px rgba(0,0,0,.06);">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h6 class="fw-semibold mb-0 d-flex align-items-center gap-2" style="font-size:.9rem;">
                        <i class="bi bi-pencil-square"></i> Traiter la réclamation
                    </h6>
                    @if(session('success'))
                        <span class="d-inline-flex align-items-center gap-1" style="font-size:.75rem;color:#27500a;">
                            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                        </span>
                    @endif
                </div>

                <form action="{{ route('admin.reclamations.update', $reclamation->id) }}"
                      method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:.8rem;color:#5f5e5a;">Statut</label>
                        <select name="statut" class="form-select" style="border-radius:8px;">
                            <option value="en_attente" @selected($reclamation->statut === 'en_attente')>
                                En attente
                            </option>
                            <option value="en_cours" @selected($reclamation->statut === 'en_cours')>
                                En cours
                            </option>
                            <option value="resolue" @selected($reclamation->statut === 'resolue')>
                                Résolue
                            </option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold" style="font-size:.8rem;color:#5f5e5a;">Réponse à l'étudiante</label>
                        <textarea name="reponse" class="form-control" rows="5"
                                  style="border-radius:8px;resize:vertical;"
                                  placeholder="Rédigez votre réponse...">{{ $reclamation->reponse }}</textarea>
                    </div>

                    <button type="submit" class="btn w-100 text-white fw-semibold d-flex align-items-center justify-content-center gap-2"
                            style="background:#0c2c4a; border-radius:8px; padding:10px;">
                        <i class="bi bi-check-lg"></i> Enregistrer
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection