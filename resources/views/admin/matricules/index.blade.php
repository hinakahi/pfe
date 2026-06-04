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
                <tr><th>Matricule</th><th>Statut</th><th>Ajouté le</th><th>Action</th></tr>
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
            <tr><td colspan="4" class="text-center text-muted">Aucun matricule ajouté.</td></tr>
            @endforelse
            </tbody>
        </table>
        {{ $matricules->links() }}
    </div>
</div>
@endsection