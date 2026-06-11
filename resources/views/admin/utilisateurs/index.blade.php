@extends('layouts.app')
@section('title', 'Gestion des utilisateurs')

@section('sidebar')
    @include('admin.partials._sidebar')
@endsection

@section('page-title', 'Gestion des utilisateurs')

@section('content')
<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <div>
            <h5 class="card-title mb-0">{{ count($utilisateurs) }} utilisateurs au total</h5>
        </div>
        <a href="{{ route('admin.utilisateurs.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i>Ajouter
        </a>
    </div>

    <div class="card-body">
        {{-- Barre de recherche et filtre --}}
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" class="form-control border-start-0" 
                           id="searchInput" placeholder="Rechercher un utilisateur...">
                </div>
            </div>
            <div class="col-md-6">
                <select class="form-select" id="roleFilter">
                    <option value="">Tous les rôles</option>
                    <option value="admin">Administrateur</option>
                    <option value="etudiante">Étudiante</option>
                    <option value="resp_hebergement">Resp. Hébergement</option>
                    <option value="technicien">Technicien</option>
                    <option value="resp_foyer">Resp. Foyer</option>
                </select>
            </div>
        </div>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 40px;"></th>
                        <th>Nom</th>
                        <th>Matricule</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Téléphone</th>
                        <th style="width: 100px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($utilisateurs as $utilisateur)
                    <tr class="utilisateur-row" data-role="{{ $utilisateur->role }}">
                        {{-- Photo --}}
                        <td>
                            <img src="{{ $utilisateur->photo ? asset('storage/'.$utilisateur->photo) : asset('images/default-avatar.png') }}"
                                 alt="{{ $utilisateur->name }}"
                                 class="rounded-circle"
                                 style="width: 40px; height: 40px; object-fit: cover;">
                        </td>
                        {{-- Nom --}}
                        <td class="fw-semibold">{{ $utilisateur->name }}</td>
                        {{-- Matricule --}}
                        <td>
                            <span class="text-muted fw-semibold">{{ $utilisateur->matricule }}</span>
                        </td>
                        {{-- Email --}}
                        <td class="text-muted">{{ $utilisateur->email }}</td>
                        {{-- Rôle --}}
                        <td>
                            @php
                                $roleColors = [
                                    'admin' => 'danger',
                                    'etudiante' => 'info',
                                    'resp_hebergement' => 'success',
                                    'technicien' => 'warning',
                                    'resp_foyer' => 'primary'
                                ];
                                $roleLabels = [
                                    'admin' => 'Admin',
                                    'etudiante' => 'Étudiante',
                                    'resp_hebergement' => 'Resp. Hébergement',
                                    'technicien' => 'Technicien',
                                    'resp_foyer' => 'Resp. Foyer'
                                ];
                            @endphp
                            <span class="badge bg-{{ $roleColors[$utilisateur->role] ?? 'secondary' }}">
                                {{ $roleLabels[$utilisateur->role] ?? $utilisateur->role }}
                            </span>
                        </td>
                        {{-- Téléphone --}}
                        <td>{{ $utilisateur->phone ?? '—' }}</td>
                        {{-- Actions --}}
                        <td>
                            <a href="{{ route('admin.utilisateurs.edit', $utilisateur) }}"
                               class="btn btn-sm btn-outline-primary" title="Modifier">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                    onclick="deleteUtilisateur({{ $utilisateur->id }})" title="Supprimer">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="bi bi-inbox"></i> Aucun utilisateur trouvé
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal de suppression --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Êtes-vous sûr de vouloir supprimer cet utilisateur ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Recherche et filtrage
document.getElementById('searchInput').addEventListener('keyup', function() {
    filterTable();
});

document.getElementById('roleFilter').addEventListener('change', function() {
    filterTable();
});

function filterTable() {
    const searchValue = document.getElementById('searchInput').value.toLowerCase();
    const roleValue = document.getElementById('roleFilter').value;
    const rows = document.querySelectorAll('.utilisateur-row');

    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        const role = row.dataset.role;
        
        const matchesSearch = text.includes(searchValue);
        const matchesRole = !roleValue || role === roleValue;

        row.style.display = (matchesSearch && matchesRole) ? '' : 'none';
    });
}

// Suppression
function deleteUtilisateur(id) {
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    const form = document.getElementById('deleteForm');
    form.action = `/admin/utilisateurs/${id}`;
    modal.show();
}
</script>
@endsection