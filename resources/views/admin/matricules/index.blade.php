@extends('layouts.app')
@section('title', 'Matricules autorisés')

@section('sidebar')
    @include('admin.partials._sidebar')
@endsection

@section('page-title', 'Matricules autorisés à s\'inscrire')

@section('content')

{{-- Formulaire ajout --}}
<div class="card mb-4">
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
                <textarea name="matricules" rows="6" class="form-control font-monospace @error('matricules') is-invalid @enderror"
                          
                          required>{{ old('matricules') }}</textarea>
                @error('matricules')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <small class="text-muted d-block mt-2">
                    📌 <strong>Préfixes reconnus:</strong>
                    <br/>• ETU = Étudiant(e) | FOY = Resp. Foyer | TEC = Technicien | ADM = Admin | HEB = Resp. Hébergement
                </small>
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
            <div class="input-group" style="max-width:320px; flex:1 1 220px;">
                <span class="input-group-text bg-white border-end-0">
                    <i class="bi bi-search text-muted"></i>
                </span>
                <input type="text" name="search" class="form-control border-start-0 ps-0"
                 placeholder="Rechercher un matricule..."
                 value="{{ request('search') }}"
                 onkeydown="if(event.key==='Enter'){this.form.submit();}">
            </div>

            <select name="statut" class="form-select" style="max-width:180px; flex:1 1 140px;" onchange="this.form.submit()">
                <option value="">Tous les statuts</option>
                <option value="disponible" {{ request('statut') == 'disponible' ? 'selected' : '' }}>
                    Disponibles
                </option>
                <option value="utilise" {{ request('statut') == 'utilise' ? 'selected' : '' }}>
                    Utilisés
                </option>
            </select>

            <select name="role" class="form-select" style="max-width:200px; flex:1 1 160px;" onchange="this.form.submit()">
                <option value="">Tous les rôles</option>
                <option value="etudiante"       {{ request('role') == 'etudiante'       ? 'selected' : '' }}> Étudiant(e)</option>
                <option value="technicien"      {{ request('role') == 'technicien'      ? 'selected' : '' }}> Technicien</option>
                <option value="resp_foyer"      {{ request('role') == 'resp_foyer'      ? 'selected' : '' }}> Resp. Foyer</option>
                <option value="resp_hebergement"{{ request('role') == 'resp_hebergement'? 'selected' : '' }}> Resp. Hébergement</option>
                <option value="admin"           {{ request('role') == 'admin'           ? 'selected' : '' }}> Admin</option>
            </select>

            
        </form>
    </div>
</div>

{{-- Liste --}}
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <span class="text-muted">{{ $matricules->total() }} matricules au total</span>
            <div class="d-flex gap-2">
                <span class="badge bg-success">{{ $matricules->where('utilise', false)->count() }} disponibles</span>
                <span class="badge bg-secondary">{{ $matricules->where('utilise', true)->count() }} utilisés</span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="min-width:120px;">Matricule</th>
                        <th style="min-width:150px;">Rôle</th>
                        <th style="min-width:120px;">Statut</th>
                        <th style="min-width:100px;">Ajouté le</th>
                        <th style="min-width:70px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($matricules as $m)
                <tr>
                    <td><code>{{ $m->matricule }}</code></td>

                    {{-- ✅ COLONNE RÔLE --}}
                    <td>
                        @if($m->role == 'etudiante')
                            <span class="badge bg-info"> Étudiant(e)</span>
                        @elseif($m->role == 'technicien')
                            <span class="badge bg-warning"> Technicien</span>
                        @elseif($m->role == 'resp_foyer')
                            <span class="badge bg-primary"> Resp. Foyer</span>
                        @elseif($m->role == 'resp_hebergement')
                            <span class="badge bg-success"> Resp. Hébergement</span>
                        @elseif($m->role == 'admin')
                            <span class="badge bg-danger"> Admin</span>
                        @else
                            <span class="badge bg-secondary">{{ $m->role }}</span>
                        @endif
                    </td>

                    {{-- ✅ COLONNE STATUT --}}
                    <td>
                        @if($m->utilise)
                            <span class="badge bg-secondary">✅ Utilisé</span>
                        @else
                            <span class="badge bg-success">⏳ Disponible</span>
                        @endif
                    </td>

                    {{-- ✅ COLONNE DATE --}}
                    <td>{{ $m->created_at->format('d/m/Y') }}</td>

                    {{-- ✅ COLONNE ACTION --}}
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
                    <td colspan="5" class="text-center py-4">
                        <div style="font-size:2rem;">🔍</div>
                        <div class="text-muted mt-1">Aucun matricule trouvé.</div>
                    </td>
                </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{ $matricules->links() }}
    </div>
</div>

@endsection