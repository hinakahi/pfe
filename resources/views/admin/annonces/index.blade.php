@extends('layouts.app')
@section('title', 'Annonces')

@section('sidebar')
    @include('admin.partials._sidebar')
@endsection

@section('page-title', 'Annonces générales')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('admin.annonces.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg me-1"></i>Nouvelle annonce
            </a>
        </div>
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr><th>Titre</th><th>Destinataires</th><th>Par</th><th>Date</th><th>Actions</th></tr>
            </thead>
            <tbody>
            @forelse($annonces as $a)
            <tr>
                <td>{{ $a->titre }}</td>
                <td><span class="badge bg-info text-dark">{{ $a->destinataire }}</span></td>
                <td>{{ $a->user->name ?? '—' }}</td>
                <td>{{ $a->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    <a href="{{ route('admin.annonces.edit', $a) }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <form method="POST" action="{{ route('admin.annonces.destroy', $a) }}" class="d-inline"
                          onsubmit="return confirm('Supprimer cette annonce ?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center text-muted">Aucune annonce.</td></tr>
            @endforelse
            </tbody>
        </table>
        {{ $annonces->links() }}
    </div>
</div>
@endsection