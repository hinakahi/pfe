{{-- resources/views/foyer/reservations/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Réservations')
@section('page-title', 'Traiter les Réservations')

@section('sidebar')
    @include('foyer.partials._sidebar')
@endsection

@section('content')

<style>
    /* ── Stat Cards ── */
    .stat-card {
        border-radius: 16px;
        padding: 1.5rem;
        color: #fff;
        position: relative;
        overflow: hidden;
        border: none;
        min-height: 110px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .stat-card .stat-label {
        font-size: 0.85rem;
        font-weight: 500;
        opacity: 0.88;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .stat-card .stat-value {
        font-size: 2.4rem;
        font-weight: 700;
        line-height: 1;
        margin-top: 0.5rem;
    }
    .stat-card .stat-icon {
        position: absolute;
        right: 1.2rem;
        top: 50%;
        transform: translateY(-50%);
        font-size: 3.5rem;
        opacity: 0.15;
    }

    /* ── Main Card ── */
    .res-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.07);
        border: none;
        overflow: hidden;
    }

    /* ── Filter Tabs ── */
    .filter-tabs {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        padding: 1.1rem 1.5rem;
        border-bottom: 1px solid #f1f5f9;
        background: #f8fafc;
    }
    .filter-tab {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 0.4rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
        text-decoration: none;
        border: 1.5px solid #e2e8f0;
        color: #64748b;
        background: #fff;
        transition: all 0.18s;
    }
    .filter-tab:hover {
        border-color: #94a3b8;
        color: #334155;
    }
    .filter-tab.active {
        background: #1a4fa0;
        border-color: #1a4fa0;
        color: #fff;
    }
    .filter-tab .tab-count {
        font-size: 0.75rem;
        padding: 0.1rem 0.5rem;
        border-radius: 10px;
        font-weight: 700;
    }
    .filter-tab.active .tab-count { background: rgba(255,255,255,0.25); color: #fff; }
    .filter-tab:not(.active) .tab-count { background: #f1f5f9; color: #64748b; }

    /* ── Table ── */
    .res-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    .res-table thead th {
        font-size: 0.78rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        color: #64748b;
        padding: 0.7rem 1.1rem;
        background: #f8fafc;
        border-bottom: 1.5px solid #e2e8f0;
    }
    .res-table tbody tr { transition: background 0.15s; }
    .res-table tbody tr:hover { background: #f0f6ff; }
    .res-table tbody td {
        padding: 0.85rem 1.1rem;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        font-size: 0.93rem;
        color: #1e293b;
    }
    .res-table tbody tr:last-child td { border-bottom: none; }

    /* ── Article thumb ── */
    .article-thumb {
        width: 44px; height: 44px;
        border-radius: 10px;
        object-fit: cover;
        box-shadow: 0 1px 4px rgba(0,0,0,0.10);
    }
    .article-thumb-placeholder {
        width: 44px; height: 44px;
        border-radius: 10px;
        background: #f1f5f9;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem; color: #94a3b8;
    }

    /* ── Ref badge ── */
    .ref-badge {
        font-family: monospace;
        font-size: 0.82rem;
        background: #f1f5f9;
        color: #475569;
        padding: 0.2rem 0.6rem;
        border-radius: 6px;
        font-weight: 600;
    }

    /* ── Qty badge ── */
    .qty-badge {
        display: inline-block;
        padding: 0.25rem 0.7rem;
        border-radius: 20px;
        font-size: 0.82rem;
        font-weight: 700;
        background: #f1f5f9;
        color: #475569;
        border: 1.5px solid #e2e8f0;
    }

    /* ── Status badges ── */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 0.28rem 0.75rem;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 600;
    }
    .status-en_attente { background: #fff7e0; color: #b45309; }
    .status-validee    { background: #dcfce7; color: #15803d; }
    .status-refusee    { background: #fee2e2; color: #b91c1c; }
    .status-annulee    { background: #f1f5f9; color: #64748b; }

    /* ── Action buttons ── */
    .action-btn {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 0.35rem 0.85rem;
        border-radius: 8px;
        font-size: 0.82rem;
        font-weight: 500;
        cursor: pointer;
        border: 1.5px solid;
        transition: all 0.15s;
        text-decoration: none;
    }
    .action-btn:hover { transform: translateY(-1px); }
    .btn-valider {
        border-color: #16a34a; color: #15803d; background: #f0fdf4;
    }
    .btn-valider:hover { background: #dcfce7; color: #15803d; }
    .btn-refuser {
        border-color: #ef4444; color: #b91c1c; background: #fef2f2;
    }
    .btn-refuser:hover { background: #fee2e2; color: #b91c1c; }

    /* ── Empty ── */
    .empty-state {
        text-align: center; padding: 4rem 1rem; color: #94a3b8;
    }
    .empty-state i { font-size: 3rem; display: block; margin-bottom: 0.75rem; }

    /* ── Modal ── */
    .modal-content { border-radius: 16px; border: none; box-shadow: 0 8px 40px rgba(0,0,0,0.15); }
    .modal-header  { background: #f8fafc; border-radius: 16px 16px 0 0; border-bottom: 1.5px solid #e2e8f0; }
    .modal-footer  { border-top: 1.5px solid #e2e8f0; }

    /* ── Pagination ── */
    .pagination-wrap {
        padding: 1rem 1.5rem;
        border-top: 1px solid #f1f5f9;
        background: #fafbfc;
    }
</style>

{{-- ── STAT CARDS ── --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg,#b85c00,#f5820d);">
            <div class="stat-label"><i class="bi bi-hourglass-split"></i> En attente</div>
            <div class="stat-value">{{ $compteurs['en_attente'] }}</div>
            <i class="bi bi-hourglass-split stat-icon"></i>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg,#0d7a4e,#1aad72);">
            <div class="stat-label"><i class="bi bi-check-circle"></i> Validées</div>
            <div class="stat-value">{{ $compteurs['validee'] }}</div>
            <i class="bi bi-check-circle stat-icon"></i>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg,#9b1c1c,#e53e3e);">
            <div class="stat-label"><i class="bi bi-x-circle"></i> Refusées</div>
            <div class="stat-value">{{ $compteurs['refusee'] }}</div>
            <i class="bi bi-x-circle stat-icon"></i>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg,#1a4fa0,#2979d8);">
            <div class="stat-label"><i class="bi bi-list-ul"></i> Total</div>
            <div class="stat-value">{{ $compteurs['en_attente'] + $compteurs['validee'] + $compteurs['refusee'] }}</div>
            <i class="bi bi-list-ul stat-icon"></i>
        </div>
    </div>
</div>

{{-- ── MAIN CARD ── --}}
<div class="res-card">

    {{-- Filter Tabs --}}
    <div class="filter-tabs">
        @foreach(['tous' => ['Toutes', 'bi-grid'], 'en_attente' => ['En attente', 'bi-hourglass-split'], 'validee' => ['Validées', 'bi-check-circle'], 'refusee' => ['Refusées', 'bi-x-circle']] as $key => $meta)
            <a href="{{ route('foyer.reservations', ['statut' => $key]) }}"
               class="filter-tab {{ $filtre === $key ? 'active' : '' }}">
                <i class="bi {{ $meta[1] }}"></i>
                {{ $meta[0] }}
                @if($key !== 'tous')
                    <span class="tab-count">{{ $compteurs[$key] }}</span>
                @endif
            </a>
        @endforeach
    </div>

    {{-- Table --}}
    <div class="table-responsive">
        <table class="res-table">
            <thead>
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
                        <span class="ref-badge">#{{ str_pad($r->id, 4, '0', STR_PAD_LEFT) }}</span>
                    </td>

                    {{-- Article --}}
                    <td>
                        <div style="display:flex; align-items:center; gap:10px;">
                            @if($r->article->photo)
                                <img src="{{ asset('storage/articles/' . $r->article->photo) }}"
                                     class="article-thumb" alt="{{ $r->nom_article }}">
                            @else
                                <div class="article-thumb-placeholder">
                                    <i class="bi bi-box-seam"></i>
                                </div>
                            @endif
                            <div>
                                <div style="font-weight:600; font-size:0.9rem;">{{ $r->nom_article }}</div>
                                <div style="font-size:0.75rem; color:#64748b;">
                                    Stock : {{ $r->article->stock }}
                                </div>
                            </div>
                        </div>
                    </td>

                    {{-- Étudiant --}}
                    <td>
                        <div style="font-weight:600; font-size:0.9rem;">{{ $r->etudiante->name ?? '—' }}</div>
                        <div style="font-size:0.75rem; color:#64748b;">{{ $r->etudiante->email ?? '' }}</div>
                    </td>

                    {{-- Quantité --}}
                    <td><span class="qty-badge">{{ $r->quantite }}</span></td>

                    {{-- Date --}}
                    <td>
                        <div style="font-size:0.88rem; font-weight:500;">{{ $r->date_reservation->format('d/m/Y') }}</div>
                        <div style="font-size:0.75rem; color:#64748b;">{{ $r->date_reservation->format('H:i') }}</div>
                    </td>

                    {{-- Statut --}}
                    <td>
                        @php
                            $info = match($r->statut) {
                                'en_attente' => ['bi-hourglass-split', 'En attente'],
                                'validee'    => ['bi-check-circle',   'Validée'],
                                'refusee'    => ['bi-x-circle',       'Refusée'],
                                'annulee'    => ['bi-slash-circle',   'Annulée'],
                                default      => ['bi-question',        $r->statut],
                            };
                        @endphp
                        <span class="status-badge status-{{ $r->statut }}">
                            <i class="bi {{ $info[0] }}"></i> {{ $info[1] }}
                        </span>
                    </td>

                    {{-- Actions --}}
                    <td>
                        @if($r->statut === 'en_attente')
                            <div style="display:flex; gap:6px;">
                                <form method="POST"
                                      action="{{ route('foyer.reservations.valider', $r) }}"
                                      onsubmit="return confirm('Valider cette réservation ?')">
                                    @csrf
                                    <button type="submit" class="action-btn btn-valider">
                                        <i class="bi bi-check-lg"></i> Valider
                                    </button>
                                </form>
                                <button class="action-btn btn-refuser"
                                        onclick="ouvrirModalRefus({{ $r->id }}, '{{ route('foyer.reservations.refuser', $r) }}')">
                                    <i class="bi bi-x-lg"></i> Refuser
                                </button>
                            </div>
                        @else
                            <span style="font-size:0.8rem; color:#94a3b8; display:flex; align-items:center; gap:5px;">
                                <i class="bi bi-clock-history"></i>
                                {{ $r->updated_at->format('d/m/Y') }}
                            </span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="bi bi-inbox"></i>
                            Aucune réservation{{ $filtre !== 'tous' ? ' dans cette catégorie' : '' }}.
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($reservations->hasPages())
        <div class="pagination-wrap">
            {{ $reservations->links() }}
        </div>
    @endif

</div>

{{-- ── MODAL REFUS ── --}}
<div class="modal fade" id="modalRefus" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">
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
                                  placeholder="Ex : stock insuffisant, article réservé à d'autres…"
                                  style="border-radius:10px; border:1.5px solid #e2e8f0;"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-x-lg me-1"></i> Confirmer le refus
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