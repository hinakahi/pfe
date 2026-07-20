<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #222; }
        h1 { font-size: 18px; margin-bottom: 4px; }
        .date-generation { color: #666; font-size: 10px; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 6px 8px; text-align: left; }
        th { background-color: #1e3a5f; color: #fff; }
        tr:nth-child(even) { background-color: #f7f7f7; }
        .badge-oui { color: #c0392b; font-weight: bold; }
        .badge-non { color: #444; }
    </style>
</head>
<body>
    <h1>Historique global du stock</h1>
    <div class="date-generation">Généré le {{ now()->format('d/m/Y à H:i') }}</div>

    <table>
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
                    {{ $u->stock->designation ?? '—' }}
                    @if($u->stock)
                        ({{ ucfirst($u->stock->categorie) }})
                    @endif
                </td>
                <td>{{ $u->quantite }} {{ $u->stock->unite ?? '' }}</td>
                <td>
                    @if($u->maintenance)
                        #{{ $u->maintenance->id }} — {{ Str::limit($u->maintenance->description, 40) }}
                    @else
                        —
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
                <td class="{{ $u->stock_epuise ? 'badge-oui' : 'badge-non' }}">
                    {{ $u->stock_epuise ? 'Oui' : 'Non' }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>