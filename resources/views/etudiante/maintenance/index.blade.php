@extends('layouts.app')

@section('page-title', 'Mes demandes de maintenance')

@section('content')
<div class="container-fluid">

    {{-- Formulaire nouvelle demande --}}
    <div class="card mb-4">
        <div class="card-header">
            <h6 class="mb-0"><i class="bi bi-plus-circle me-2 text-primary"></i>Nouvelle demande de maintenance</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('etudiante.maintenance.store') }}">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Chambre <span class="text-danger">*</span></label>
                        <select name="chambre_id" class="form-select @error('chambre_id') is-invalid @enderror" required>
                            <option value="">-- Sélectionnez une chambre --</option>
                            @foreach($chambres as $chambre)
                                <option value="{{ $chambre->id }}" {{ old('chambre_id') == $chambre->id ? 'selected' : '' }}>
                                    Chambre {{ $chambre->numero }}
                                </option>
                            @endforeach
                        </select>
                        @error('chambre_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Type de panne <span class="text-danger">*</span></label>
                        <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                            <option value="">-- Sélectionnez un type --</option>
                            <option value="electricite" {{ old('type') === 'electricite' ? 'selected' : '' }}>⚡ Électricité</option>
                            <option value="plomberie"   {{ old('type') === 'plomberie'   ? 'selected' : '' }}>🔧 Plomberie</option>
                            <option value="menuiserie"  {{ old('type') === 'menuiserie'  ? 'selected' : '' }}>🪚 Menuiserie</option>
                            <option value="autre"       {{ old('type') === 'autre'       ? 'selected' : '' }}>🔩 Autre</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
                        <textarea name="description" rows="3"
                                  class="form-control @error('description') is-invalid @enderror"
                                  placeholder="Décrivez le problème en détail…" required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Urgence <span class="text-danger">*</span></label>
                        <div class="d-flex flex-column gap-2 mt-1">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="urgence"
                                       id="normale" value="normale"
                                       {{ old('urgence', 'normale') === 'normale' ? 'checked' : '' }}>
                                <label class="form-check-label" for="normale">
                                    <span class="badge bg-secondary">Normale</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="urgence"
                                       id="urgente" value="urgente"
                                       {{ old('urgence') === 'urgente' ? 'checked' : '' }}>
                                <label class="form-check-label" for="urgente">
                                    <span class="badge bg-danger">Urgente</span>
                                </label>
                            </div>
                        </div>
                        @error('urgence')
                            <div class="text-danger" style="font-size:.85rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send me-1"></i>Envoyer la demande
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Liste des demandes --}}
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0"><i class="bi bi-list-check me-2 text-primary"></i>Mes demandes</h6>
            <small class="text-muted">{{ $demandes->count() }} demande(s) au total</small>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Chambre</th>
                            <th>Urgence</th>
                            <th>Statut</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($demandes as $d)
                        <tr>
                            <td class="text-muted" style="font-size:.8rem;">{{ $d->id }}</td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    {{ ucfirst($d->type) }}
                                </span>
                            </td>
                            <td style="max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                {{ $d->description }}
                            </td>
                            <td>{{ $d->chambre->numero ?? '-' }}</td>
                            <td>
                                @if($d->urgence === 'urgente')
                                    <span class="badge bg-danger">Urgente</span>
                                @else
                                    <span class="badge bg-secondary">Normale</span>
                                @endif
                            </td>
                            <td>
                                @if($d->statut === 'en_attente')
                                    <span class="badge bg-warning text-dark">En attente</span>
                                @elseif($d->statut === 'en_cours')
                                    <span class="badge bg-primary">En cours</span>
                                @else
                                    <span class="badge bg-success">Terminée ✓</span>
                                @endif
                            </td>
                            <td style="font-size:.8rem;">{{ $d->date_signalement?->format('d/m/Y') }}</td>
                            <td>
                                @if($d->statut === 'en_attente')
                                <form method="POST" action="{{ route('etudiante.maintenance.destroy', $d->id) }}"
                                      onsubmit="return confirm('Annuler cette demande ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-x-circle"></i> Annuler
                                    </button>
                                </form>
                                @else
                                    <span class="text-muted" style="font-size:.8rem;">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-2 opacity-25"></i>
                                <p class="mt-2">Aucune demande de maintenance.</p>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection