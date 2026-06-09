@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="bi bi-exclamation-circle me-2"></i>Mes Réclamations</h4>
        <a href="{{ route('etudiante.reclamations.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Nouvelle réclamation
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Sujet</th>
                        <th>Statut</th>
                        <th>Réponse</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reclamations as $r)
                    <tr>
                        <td class="fw-semibold">{{ Str::limit($r->sujet, 45) }}</td>
                        <td>
                            @php
                                $badge = match($r->statut) {
                                    'en_attente' => ['bg' => 'warning', 'label' => '⏳ En attente'],
                                    'traitee'    => ['bg' => 'success', 'label' => '✅ Traitée'],
                                    'fermee'     => ['bg' => 'secondary', 'label' => '🔒 Fermée'],
                                };
                            @endphp
                            <span class="badge bg-{{ $badge['bg'] }}">{{ $badge['label'] }}</span>
                        </td>
                        <td>
                            @if($r->reponse)
                                <span class="text-success">
                                    <i class="bi bi-check-circle-fill"></i> Répondu
                                </span>
                            @else
                                <span class="text-muted">
                                    <i class="bi bi-hourglass-split"></i> En attente
                                </span>
                            @endif
                        </td>
                        <td class="text-muted small">
                            {{ $r->date_reclamation->format('d/m/Y') }}
                        </td>
                        <td>
                            <a href="{{ route('etudiante.reclamations.show', $r) }}"
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i> Voir
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-5">
                            <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                            Vous n'avez aucune réclamation.
                            <div class="mt-2">
                                <a href="{{ route('etudiante.reclamations.create') }}" 
                                   class="btn btn-sm btn-primary">
                                    Soumettre une réclamation
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $reclamations->links() }}</div>
</div>
@endsection