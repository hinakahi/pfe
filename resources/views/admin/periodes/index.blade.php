@extends('layouts.app')
@section('title', 'Périodes')

@section('sidebar')
    @include('admin.partials._sidebar')
@endsection

@section('page-title', 'Périodes de renouvellement / changement')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('admin.periodes.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg me-1"></i>Nouvelle période
            </a>
        </div>
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr><th>Libellé</th><th>Type</th><th>Début</th><th>Fin</th><th>Statut</th><th>Actions</th></tr>
            </thead>
            <tbody>
            @forelse($periodes as $p)
            <tr>
                <td>{{ $p->libelle }}</td>
                <td><span class="badge bg-{{ $p->type=='renouvellement' ? 'primary' : 'warning text-dark' }}">{{ $p->type }}</span></td>
                <td>{{ $p->date_debut->format('d/m/Y') }}</td>
                <td>{{ $p->date_fin->format('d/m/Y') }}</td>
                <td>
                    @if($p->isOuverte())
                        <span class="badge bg-success">Ouverte</span>
                    @elseif($p->active)
                        <span class="badge bg-secondary">Inactive</span>
                    @else
                        <span class="badge bg-danger">Fermée</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.periodes.edit', $p) }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <form method="POST" action="{{ route('admin.periodes.destroy', $p) }}" class="d-inline"
                          onsubmit="return confirm('Supprimer cette période ?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center text-muted">Aucune période.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection