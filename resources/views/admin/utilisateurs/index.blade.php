@extends('layouts.app')
@section('title', 'Utilisateurs')

@section('sidebar')
    @include('admin.partials._sidebar')
@endsection

@section('page-title', 'Gestion des utilisateurs')

@section('content')
<div class="card">
    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-muted">
                {{ $utilisateurs->total() }} utilisateurs au total
            </span>

            <a href="{{ route('admin.utilisateurs.create') }}"
               class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg me-1"></i>Ajouter
            </a>
        </div>

        <!-- Recherche + filtre -->
        <div class="row mb-3">
            <div class="col-md-8">
                <input
                    type="text"
                    id="searchInput"
                    class="form-control"
                    placeholder="Rechercher un utilisateur..."
                >
            </div>

            <div class="col-md-4">
                <select id="roleFilter" class="form-select">
                    <option value="">Tous les rôles</option>
                    <option value="admin">Administrateur</option>
                    <option value="etudiante">Étudiante</option>
                    <option value="resp_foyer">Responsable foyer</option>
                    <option value="resp_hebergement">Responsable hébergement</option>
                    <option value="technicien">Technicien</option>
                </select>
            </div>
        </div>

        <table class="table table-hover align-middle" id="usersTable">
            <thead class="table-light">
                <tr>
                    <th>Nom</th>
                    <th>Matricule</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Téléphone</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
            @forelse($utilisateurs as $u)
            <tr>
                <td>{{ $u->name }}</td>

                <td>
                    <code>{{ $u->matricule }}</code>
                </td>

                <td>{{ $u->email }}</td>

                <td>
                    @php
                        $colors = [
                            'admin' => 'danger',
                            'etudiante' => 'primary',
                            'resp_hebergement' => 'success',
                            'technicien' => 'warning',
                            'resp_foyer' => 'info'
                        ];
                    @endphp

                    <span class="badge bg-{{ $colors[$u->role] ?? 'secondary' }} role-badge">
                        {{ $u->role }}
                    </span>
                </td>

                <td>{{ $u->phone ?? '—' }}</td>

                <td>
                    <a href="{{ route('admin.utilisateurs.edit', $u) }}"
                       class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-pencil"></i>
                    </a>

                    @if($u->id !== auth()->id())
                    <form method="POST"
                          action="{{ route('admin.utilisateurs.destroy', $u) }}"
                          class="d-inline"
                          onsubmit="return confirm('Supprimer cet utilisateur ?')">

                        @csrf
                        @method('DELETE')

                        <button class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center text-muted">
                    Aucun utilisateur.
                </td>
            </tr>
            @endforelse
            </tbody>
        </table>

        {{ $utilisateurs->links() }}

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const searchInput = document.getElementById('searchInput');
    const roleFilter = document.getElementById('roleFilter');

    function filterTable() {

        const searchValue = searchInput.value.toLowerCase();
        const selectedRole = roleFilter.value.toLowerCase();

        const rows = document.querySelectorAll('#usersTable tbody tr');

        rows.forEach(row => {

            const text = row.textContent.toLowerCase();

            const roleText =
                row.querySelector('.role-badge')
                ?.textContent
                .toLowerCase()
                .trim() || '';

            const matchSearch = text.includes(searchValue);

            const matchRole =
                selectedRole === '' ||
                roleText === selectedRole;

            row.style.display =
                (matchSearch && matchRole)
                ? ''
                : 'none';
        });
    }

    searchInput.addEventListener('keyup', filterTable);
    roleFilter.addEventListener('change', filterTable);

});
</script>

@endsection