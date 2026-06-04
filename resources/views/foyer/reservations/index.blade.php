{{-- resources/views/foyer/reservations/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Réservations')
@section('page-title', 'Traiter les Réservations')

@section('sidebar')
    @include('foyer.partials._sidebar')
@endsection

@section('content')

{{-- ── En-tête + compteurs ─────────────────────────────────── --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg,#f59e0b,#d97706);">
            <div class="number">{{ $compteurs['en_attente'] }}</div>
            <div class="label"><i class="bi bi-hourglass-split me-1"></i>En attente</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg,#22c55e,#16a34a);">
            <div class="number">{{ $compteurs['validee'] }}</div>
            <div class="label"><i class="bi bi-check-circle me-1"></i>Validées</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg,#ef4444,#dc2626);">
            <div class="number">{{ $compteurs['refusee'] }}</div>
            <div class="label"><i class="bi bi-x-circle me-1"></i>Refusées</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg,#3b82f6,#2563eb);">
            <div class="number">{{ $compteurs['en_attente'] + $compteurs['validee'] + $compteurs['refusee'] }}</div>
            <div class="label"><i class="bi bi-list-ul me-1"></i>Total</div>
        </div>
    </div>
</div>

{{-- ── Onglets filtre ──────────────────────────────────────── --}}
<div class="card mb-3">
    <div class="card-body py-2">
        <div class="d-flex gap-2 flex-wrap">
            @foreach(['tous' => 'Toutes', 'en_attente' => 'En attente', 'validee' => 'Validées', 'refusee' => 'Refusées'] as $key => $label)
                <a href="{{ route('foyer.reservations', ['statut' => $key]) }}"
                   class="btn btn-sm {{ $filtre === $key ? 'btn-primary' : 'btn-outline-secondary' }}">
                    {{ $label }}
                    @if($key !== 'tous')
                        <span class="badge {{ $filtre === $key ? 'bg-white text-primary' : 'bg-secondary' }} ms-1">
                            {{ $compteurs[$key] }}
                        </span>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
</div>

{{-- ── Tableau ─────────────────────────────────────────────── --}}
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Réf.</th>
                        <th>Article</th>
                        <th>Étudiant(e)</th>
                        <th>Qté</th>
                        <th>Date</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservations as $r)
                    <tr>
                        {{-- Référence --}}
                        <td>
                            <code class="text-muted">#{{ str_pad($r->id, 4, '0', STR_PAD_LEFT) }}</code>
                        </td>

                        {{-- Article --}}
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if($r->article->photo)
                                    <img src="{{ asset('storage/articles/' . $r->article->photo) }}"
                                         width="36" height="36"
                                         style="border-radius:8px; object-fit:cover;">
                                @else
                                    <div style="width:36px; height:36px; border-radius:8px;
                                                background:#e9ecef; display:flex; align-items:center;
                                                justify-content:center; font-size:.85rem;">
                                        📦
                                    </div>
                                @endif
                                <div>
                                    <div class="fw-semibold" style="font-size:.9rem;">{{ $r->nom_article }}</div>
                                    <div class="text-muted" style="font-size:.75rem;">
                                        Stock : {{ $r->article->stock }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- Étudiant --}}
                        <td>
                            <div class="fw-semibold" style="font-size:.9rem;">{{ $r->etudiante->name ?? '—' }}</div>
                            <div class="text-muted" style="font-size:.75rem;">{{ $r->etudiante->email ?? '' }}</div>
                        </td>

                        {{-- Quantité --}}
                        <td>
                            <span class="badge bg-light text-dark border">{{ $r->quantite }}</span>
                        </td>

                        {{-- Date --}}
                        <td style="font-size:.85rem; color:var(--text-muted);">
                            {{ $r->date_reservation->format('d/m/Y') }}<br>
                            <small>{{ $r->date_reservation->format('H:i') }}</small>
                        </td>

                        {{-- Statut --}}
                        <td>
                            @php
                                $badge = match($r->statut) {
                                    'en_attente' => ['bg-warning text-dark', 'bi-hourglass-split', 'En attente'],
                                    'validee'    => ['bg-success',           'bi-check-circle',   'Validée'],
                                    'refusee'    => ['bg-danger',            'bi-x-circle',       'Refusée'],
                                    'annulee'    => ['bg-secondary',         'bi-slash-circle',   'Annulée'],
                                    default      => ['bg-secondary',         'bi-question',       $r->statut],
                                };
                            @endphp
                            <span class="badge {{ $badge[0] }}">
                                <i class="bi {{ $badge[1] }} me-1"></i>{{ $badge[2] }}
                            </span>
                        </td>

                        {{-- Actions --}}
                        <td>
                            @if($r->statut === 'en_attente')
                                <div class="d-flex gap-1">
                                    {{-- Valider --}}
                                    <form method="POST"
                                          action="{{ route('foyer.reservations.valider', $r) }}"
                                          onsubmit="return confirm('Valider cette réservation ?')">
                                        @csrf
                                        <button class="btn btn-success btn-sm">
                                            <i class="bi bi-check-lg"></i> Valider
                                        </button>
                                    </form>

                                    {{-- Refuser --}}
                                    <button class="btn btn-danger btn-sm"
                                            onclick="ouvrirModalRefus({{ $r->id }}, '{{ route('foyer.reservations.refuser', $r) }}')">
                                        <i class="bi bi-x-lg"></i> Refuser
                                    </button>
                                </div>
                            @else
                                <span class="text-muted" style="font-size:.8rem;">
                                    <i class="bi bi-clock-history me-1"></i>
                                    {{ $r->updated_at->format('d/m/Y') }}
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                            Aucune réservation{{ $filtre !== 'tous' ? ' dans cette catégorie' : '' }}.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($reservations->hasPages())
            <div class="px-3 py-3 border-top">
                {{ $reservations->links() }}
            </div>
        @endif
    </div>
</div>

{{-- ── Modal refus ─────────────────────────────────────────── --}}
<div class="modal fade" id="modalRefus" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-x-circle text-danger me-2"></i>Refuser la réservation
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formRefus" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Motif du refus
                            <span class="text-muted fw-normal">(optionnel)</span>
                        </label>
                        <textarea name="motif_refus" class="form-control" rows="3"
                                  placeholder="Ex : stock insuffisant, article réservé à d'autres…"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-x-lg me-1"></i>Confirmer le refus
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
const modalRefus = new bootstrap.Modal(document.getElementById('modalRefus'));

function ouvrirModalRefus(id, url) {
    document.getElementById('formRefus').action = url;
    modalRefus.show();
}
</script>
@endsection