@extends('layouts.app')
@section('title', 'Réclamations')
@section('page-title', 'Gestion des réclamations')

@section('content')

{{-- Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1 fw-bold"> Réclamations</h4>
        <p class="mb-0 small" style="opacity:0.6;">Réclamations soumises par les étudiantes</p>
    </div>
    <span class="badge rounded-pill px-3 py-2 fs-6"
          style="background:linear-gradient(135deg,#1a3c5e,#2d6a9f);color:#fff;">
        {{ $reclamations->total() }} réclamation(s)
    </span>
</div>

{{-- Recherche + Filtre --}}
<div class="card border-0 shadow-sm mb-4" style="border-radius:12px;">
    <div class="card-body py-3">
        <form method="GET" class="d-flex gap-3 align-items-center flex-wrap">
            <div class="input-group" style="max-width:320px;">
                <span class="input-group-text bg-white border-end-0">
                    <i class="bi bi-search text-muted"></i>
                </span>
                <input type="text" name="search"
                       class="form-control border-start-0 ps-0"
                       placeholder="Rechercher par nom ou sujet..."
                       value="{{ request('search') }}">
            </div>

            <select name="statut" class="form-select" style="max-width:180px;">
                <option value="">Tous les statuts</option>
                <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>
                    En attente
                </option>
                <option value="traitee" {{ request('statut') == 'traitee' ? 'selected' : '' }}>
                    Traitée
                </option>
                <option value="fermee" {{ request('statut') == 'fermee' ? 'selected' : '' }}>
                     Fermée
                </option>
            </select>

            <button type="submit" class="btn text-white px-4"
                    style="background:linear-gradient(135deg,#1a3c5e,#2d6a9f);border-radius:8px;">
                Filtrer
            </button>

            @if(request('search') || request('statut'))
                <a href="{{ route('admin.reclamations.index') }}"
                   class="btn btn-outline-secondary" style="border-radius:8px;">
                    Réinitialiser
                </a>
            @endif
        </form>
    </div>
</div>

{{-- Tableau --}}
<div class="card border-0 shadow-sm" style="border-radius:12px;">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr style="background:#f8f9fa;">
                    <th class="ps-4 py-3">Étudiante</th>
                    <th>Sujet</th>
                    <th>Date</th>
                    <th>Statut</th>
                    <th class="pe-4">Action</th>
                </tr>
            </thead>
            <tbody>
            @forelse($reclamations as $rec)
            <tr>
                <td class="ps-4">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                             style="width:34px;height:34px;
                                    background:linear-gradient(135deg,#1a3c5e,#2d6a9f);
                                    color:#fff;font-weight:700;font-size:0.85rem;flex-shrink:0;">
                            {{ strtoupper(substr($rec->etudiante->name, 0, 1)) }}
                        </div>
                        <span class="fw-semibold">{{ $rec->etudiante->name }}</span>
                    </div>
                </td>
                <td>{{ Str::limit($rec->sujet, 40) }}</td>
                <td class="text-muted small">{{ $rec->date_reclamation->format('d/m/Y') }}</td>
                <td>
                    @if($rec->statut === 'en_attente')
                        <span class="badge rounded-pill bg-warning text-dark px-3">En attente</span>
                    @elseif($rec->statut === 'traitee')
                        <span class="badge rounded-pill bg-success px-3">Traitée</span>
                    @else
                        <span class="badge rounded-pill bg-secondary px-3">Fermée</span>
                    @endif
                </td>
                <td class="pe-4">
                    <a href="{{ route('admin.reclamations.show', $rec->id) }}"
                       class="btn btn-sm text-white px-3"
                       style="background:linear-gradient(135deg,#1a3c5e,#2d6a9f);border-radius:8px;">
                        <i class="bi bi-eye me-1"></i> Voir
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center py-5">
                    <div style="font-size:2.5rem;">📭</div>
                    <div class="mt-2" style="opacity:0.6;">Aucune réclamation trouvée.</div>
                </td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @if($reclamations->hasPages())
    <div class="card-footer bg-white border-0 py-3">
        {{ $reclamations->links() }}
    </div>
    @endif
</div>

@endsection