@extends('layouts.app')

@section('page-title', 'Historique global du stock')

@section('content')
<div class="container-fluid">

    <a href="{{ route('technicien.stock.index') }}" class="btn btn-sm btn-outline-secondary mb-3">
        <i class="bi bi-arrow-left me-1"></i>Retour au stock
    </a>

 

    <div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('technicien.stock.historique-global') }}">
            <div class="row g-2 align-items-center">
                <div class="col-md-4">
                    <select name="stock_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Tous les matériels</option>
                        @foreach($stocks as $s)
                            <option value="{{ $s->id }}" {{ request('stock_id') == $s->id ? 'selected' : '' }}>
                                {{ $s->designation }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="semestre" class="form-select" onchange="this.form.submit()">
                        <option value="">Tous les semestres</option>
                        <option value="s1" {{ request('semestre') === 's1' ? 'selected' : '' }}>Semestre 1 (Sept - Jan)</option>
                        <option value="s2" {{ request('semestre') === 's2' ? 'selected' : '' }}>Semestre 2 (Fév - Juin)</option>
                    </select>
                </div>
                   <div class="col-md-3 text-end">
    <a href="{{ route('technicien.stock.historique-global.pdf', request()->query()) }}" class="btn btn-outline-danger text-nowrap">
        <i class="bi bi-file-earmark-pdf me-1"></i>Exporter en PDF
    </a>
</div>
                <div class="col-md-3">
                    @if(request('stock_id') || request('semestre'))
                        <a href="{{ route('technicien.stock.historique-global') }}" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-x-lg"></i> Réinitialiser
                        </a>
                    @endif
                </div>
              
            </div>
        </form>
    </div>
</div>

    @if($utilisations->count())
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Matériel</th>
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
                    <td>
                        <strong>{{ $u->stock->designation ?? '—' }}</strong>
                        @if($u->stock)
                            <br><small class="text-muted">{{ ucfirst($u->stock->categorie) }}</small>
                        @endif
                    </td>
                    <td><span class="fw-bold">{{ $u->quantite }}</span> {{ $u->stock->unite ?? '' }}</td>
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
                        @elseif($u->maintenance && $u->maintenance->lieu_commun)
                            {{ $u->maintenance->lieu_commun }}
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
        {{ $utilisations->appends(request()->query())->links() }}
    </div>

    @else
    <div class="card">
        <div class="card-body text-center text-muted py-5">
            <i class="bi bi-inbox fs-1 opacity-25"></i>
            <p class="mt-2 mb-0">Aucune utilisation de matériel enregistrée pour le moment.</p>
        </div>
    </div>
    @endif

</div>
@endsection