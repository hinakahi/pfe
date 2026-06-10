@extends('layouts.app')
@section('title', 'Matricules autorisés')

@section('sidebar')
    @include('admin.partials._sidebar')
@endsection

@section('page-title', 'Matricules autorisés à s\'inscrire')

@section('content')

{{-- Formulaire ajout --}}
<div class="card mb-4" style="max-width:560px">
    <div class="card-body">
        <h6 class="card-title mb-3">
            <i class="bi bi-plus-circle me-2"></i>Ajouter des matricules
        </h6>
        <form method="POST" action="{{ route('admin.matricules.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">
                    Matricules <span class="text-muted small">(un par ligne)</span>
                </label>
                <textarea name="matricules" rows="5" class="form-control font-monospace"
                          placeholder="ETU2024001&#10;ETU2024002&#10;ETU2024003" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg me-1"></i>Ajouter
            </button>
        </form>
    </div>
</div>

{{-- Recherche + Filtre --}}
<div class="card border-0 shadow-sm mb-4" style="border-radius:12px;">
    <div class="card-body py-3">
        <form method="GET" class="d-flex gap-3 align-items-center flex-wrap">
            <div class="input-group" style="max-width:320px;">
                <span class="input-group-text bg-white border-end-0">
                    <i class="bi bi-search text-muted"></i>
                </span>
                <input type="text" name="search" class="form-control border-start-0 ps-0"
                       placeholder="Rechercher un matricule..."
                       value="{{ request('search') }}">
            </div>

            <select name="statut" class="form-select" style="max-width:180px;">
                <option value="">Tous</option>
                <option value="disponible" {{ request('statut') == 'disponible' ? 'selected' : '' }}>
                    Disponibles
                </option>
                <option value="utilise" {{ request('statut') == 'utilise' ? 'selected' : '' }}>
                     Utilisés
                </option>
            </select>

            <button type="submit" class="btn text-white px-4"
                    style="background:linear-gradient(135deg,#1a3c5e,#2d6a9f);border-radius:8px;">
                Filtrer
            </button>

            @if(request('search') || request('statut'))
                <a href="{{ route('admin.matricules.index') }}"
                   class="btn btn-outline-secondary" style="border-radius:8px;">
                    Réinitialiser
                </a>
            @endif
        </form>
    </div>
</div>

{{-- Liste --}}
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-muted">{{ $matricules->total() }} matricules au total</span>
            <div class="d-flex gap-2">
                <span class="badge bg-success">{{ $matricules->where('utilise', false)->count() }} disponibles</span>
                <span class="badge bg-secondary">{{ $matricules->where('utilise', true)->count() }} utilisés</span>
            </div>
        </div>

        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Matricule</th>
                    <th>Statut</th>
                    <th>Ajouté le</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            @forelse($matricules as $m)
            <tr>
                <td><code>{{ $m->matricule }}</code></td>
                <td>
                    @if($m->utilise)
                        <span class="badge bg-secondary">Utilisé</span>
                    @else
                        <span class="badge bg-success">Disponible</span>
                    @endif
                </td>
                <td>{{ $m->created_at->format('d/m/Y') }}</td>
                <td>
                    @if(!$m->utilise)
                    <form method="POST" action="{{ route('admin.matricules.destroy', $m) }}"
                          class="d-inline" onsubmit="return confirm('Supprimer ce matricule ?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center py-4">
                    <div style="font-size:2rem;">🔍</div>
                    <div class="text-muted mt-1">Aucun matricule trouvé.</div>
                </td>
            </tr>
            @endforelse
            </tbody>
        </table>

        {{ $matricules->links() }}
    </div>
</div>

@endsection