@extends('layouts.app')

@section('page-title', 'Historique du matériel')

@section('content')
<div class="container-fluid">

    <a href="{{ route('technicien.stock.index') }}" class="btn btn-sm btn-outline-secondary mb-3">
        <i class="bi bi-arrow-left me-1"></i>Retour au stock
    </a>

    <div class="card mb-4">
        <div class="card-body d-flex align-items-center gap-3">
            @if($stock->photo)
                <img src="{{ asset('storage/'.$stock->photo) }}" style="width:70px;height:70px;object-fit:cover;border-radius:10px;">
            @else
                <div style="width:70px;height:70px;border-radius:10px;background:var(--bg-body);display:flex;align-items:center;justify-content:center;">
                    <i class="bi bi-box-seam fs-3 text-muted"></i>
                </div>
            @endif
            <div>
                <h5 class="mb-1">{{ $stock->designation }}</h5>
                <span class="text-muted">{{ ucfirst($stock->categorie) }}</span>
                &middot;
                <span class="fw-bold">{{ $stock->quantite }} {{ $stock->unite }}</span> en stock
            </div>
        </div>
    </div>

    <h6 class="mb-3"><i class="bi bi-clock-history me-1"></i>Historique d'utilisation</h6>

    @if($utilisations->count())
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Quantité utilisée</th>
                    <th>Demande liée</th>
                    <th>Technicien</th>
                    <th>Localisation</th>
                    <th>Stock épuisé ?</th>
                </tr>
            </thead>
            <tbody>
                @foreach($utilisations as $u)
                <tr>
                    <td>{{ $u->created_at->format('d/m/Y H:i') }}</td>
                    <td><span class="fw-bold">{{ $u->quantite }}</span> {{ $stock->unite }}</td>
                    <td>
                        @if($u->maintenance)
                            <a href="{{ route('technicien.demandes.show', $u->maintenance->id) }}">
                                #{{ $u->maintenance->id }} — {{ Str::limit($u->maintenance->description, 40) }}
                            </a>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>{{ $u->maintenance->technicien->name ?? '—' }}</td>
                    <td>
                        @if($u->maintenance && $u->maintenance->chambre)
                            Chambre {{ $u->maintenance->chambre->numero }} (Bloc {{ $u->maintenance->chambre->bloc }})
                        @else
                            —
                        @endif
                    </td>
                    <td>
                        @if($u->stock_epuise)
                            <span class="badge bg-danger">Oui</span>
                        @else
                            <span class="badge bg-light text-dark border">Non</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $utilisations->links() }}
    </div>

    @else
    <div class="card">
        <div class="card-body text-center text-muted py-5">
            <i class="bi bi-inbox fs-1 opacity-25"></i>
            <p class="mt-2 mb-0">Ce matériel n'a jamais été utilisé pour une intervention.</p>
        </div>
    </div>
    @endif

</div>
@endsection