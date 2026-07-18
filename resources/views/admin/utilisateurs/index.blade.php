@extends('layouts.app')
@section('title', 'Gestion des utilisateurs')

@section('sidebar')
    @include('admin.partials._sidebar')
@endsection

@section('page-title', 'Gestion des utilisateurs')

@section('content')

{{-- ─── Cartes Actifs / Archivés ─────────────────────────── --}}
<div class="row mb-4">
    <div class="col-md-6">
        <a href="{{ route('admin.utilisateurs.index', ['statut' => 'actif']) }}" class="text-decoration-none">
            <div class="card h-100 {{ $statut === 'actif' ? 'border-success shadow-sm' : '' }}">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted mb-1">Comptes actifs</h6>
                        <h3 class="mb-0 {{ $statut === 'actif' ? 'text-success' : '' }}">{{ $countActifs }}</h3>
                    </div>
                    <i class="bi bi-people-fill fs-1 {{ $statut === 'actif' ? 'text-success' : 'text-muted' }}"></i>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-6">
        <a href="{{ route('admin.utilisateurs.index', ['statut' => 'archive']) }}" class="text-decoration-none">
            <div class="card h-100 {{ $statut === 'archive' ? 'border-danger shadow-sm' : '' }}">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted mb-1">Comptes archivés</h6>
                        <h3 class="mb-0 {{ $statut === 'archive' ? 'text-danger' : '' }}">{{ $countArchives }}</h3>
                    </div>
                    <i class="bi bi-archive-fill fs-1 {{ $statut === 'archive' ? 'text-danger' : 'text-muted' }}"></i>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <div>
            <h5 class="card-title mb-0">
                {{ count($utilisateurs) }} utilisateur(s)
                @if($statut === 'archive')
                    <span class="badge bg-danger ms-2">Archivés</span>
                @else
                    <span class="badge bg-success ms-2">Actifs</span>
                @endif
            </h5>
        </div>
        @if($statut === 'actif')
        <a href="{{ route('admin.utilisateurs.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i>Ajouter
        </a>
        @endif
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

        {{-- ─── Formulaire d'action groupée ─────────────────── --}}
        <form id="bulkForm" method="POST"
              action="{{ $statut === 'archive' ? route('admin.utilisateurs.bulk-restore') : route('admin.utilisateurs.bulk-archive') }}">
            @csrf

            <div class="mb-2">
                <button type="submit" id="bulkActionBtn"
                        class="btn btn-sm {{ $statut === 'archive' ? 'btn-success' : 'btn-warning' }}"
                        disabled
                        onclick="return confirm('{{ $statut === 'archive' ? 'Réactiver les comptes sélectionnés ?' : 'Archiver les comptes sélectionnés ?' }}')">
                    <i class="bi bi-{{ $statut === 'archive' ? 'arrow-counterclockwise' : 'archive' }} me-1"></i>
                    {{ $statut === 'archive' ? 'Réactiver la sélection' : 'Archiver la sélection' }}
                </button>
            </div>

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 40px;">
                                <input type="checkbox" id="checkAll" class="form-check-input">
                            </th>
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
                            {{-- Checkbox --}}
                            <td>
                                <input type="checkbox" name="ids[]" value="{{ $utilisateur->id }}"
                                       class="form-check-input rowCheck">
                            </td>
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
@if($statut === 'archive')
    <button type="button" class="btn btn-sm btn-outline-success"
            onclick="restoreUtilisateur({{ $utilisateur->id }})" title="Réactiver">
        <i class="bi bi-arrow-counterclockwise"></i>
    </button>
    <button type="button" class="btn btn-sm btn-outline-dark"
            onclick="forceDeleteUtilisateur({{ $utilisateur->id }})" title="Supprimer définitivement">
        <i class="bi bi-trash3-fill"></i>
    </button>
@else
                                    <a href="{{ route('admin.utilisateurs.edit', $utilisateur) }}"
                                       class="btn btn-sm btn-outline-primary" title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                            onclick="deleteUtilisateur({{ $utilisateur->id }})" title="Archiver">
                                        <i class="bi bi-archive"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox"></i>
                                @if($statut === 'archive')
                                    Aucun compte archivé
                                @else
                                    Aucun utilisateur trouvé
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>

        <div class="mt-3">
            {{ $utilisateurs->links() }}
        </div>
    </div>
</div>

{{-- Modal d'archivage individuel --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmer l'archivage</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Êtes-vous sûr de vouloir archiver cet utilisateur ? Il n'apparaîtra plus dans la liste des comptes actifs.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger">Archiver</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Formulaire caché pour la réactivation individuelle --}}
<form id="restoreForm" method="POST" style="display: none;">
    @csrf @method('PATCH')
</form>
<form id="forceDeleteForm" method="POST" style="display: none;">
    @csrf @method('DELETE')
</form>

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

// Archivage individuel
function deleteUtilisateur(id) {
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    const form = document.getElementById('deleteForm');
    form.action = `/admin/utilisateurs/${id}`;
    modal.show();
}

// Réactivation individuelle
function restoreUtilisateur(id) {
    if (!confirm('Réactiver cet utilisateur ?')) return;
    const form = document.getElementById('restoreForm');
    form.action = `/admin/utilisateurs/${id}/restore`;
    form.submit();
}
function forceDeleteUtilisateur(id) {
    if (!confirm('⚠️ Cette action est irréversible. Supprimer définitivement ce compte et toutes ses données ?')) return;
    const form = document.getElementById('forceDeleteForm');
    form.action = `/admin/utilisateurs/${id}/force-delete`;
    form.submit();
}

// Sélection groupée : checkbox "tout sélectionner"
document.getElementById('checkAll').addEventListener('change', function () {
    document.querySelectorAll('.rowCheck').forEach(cb => cb.checked = this.checked);
    toggleBulkButton();
});

// Activer/désactiver le bouton d'action groupée selon la sélection
document.querySelectorAll('.rowCheck').forEach(cb => {
    cb.addEventListener('change', toggleBulkButton);
});

function toggleBulkButton() {
    const anyChecked = document.querySelectorAll('.rowCheck:checked').length > 0;
    document.getElementById('bulkActionBtn').disabled = !anyChecked;
}
</script>
@endsection