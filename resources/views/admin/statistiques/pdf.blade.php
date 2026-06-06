<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h1 { color: #2d6a9f; font-size: 18px; margin-bottom: 5px; }
        p { color: #666; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 25px; }
        th { background: #2d6a9f; color: white; padding: 8px; text-align: left; }
        td { padding: 7px 8px; border-bottom: 1px solid #ddd; }
        tr:nth-child(even) { background: #f5f5f5; }
        h2 { color: #2d6a9f; font-size: 14px; margin-bottom: 8px; }
    </style>
</head>
<body>

    <h1>📊 Statistiques Maintenance</h1>
    <p>Période : {{ ucfirst($periode) }} — Exporté le {{ now()->format('d/m/Y à H:i') }}</p>

    {{-- 1. Pannes par type --}}
    <h2>🔧 Taux de pannes par type</h2>
    <table>
        <thead><tr><th>Type de panne</th><th>Nombre</th></tr></thead>
        <tbody>
            @foreach($pannesParType as $type => $nb)
            <tr><td>{{ $type }}</td><td>{{ $nb }}</td></tr>
            @endforeach
        </tbody>
    </table>

    {{-- 2. Délai moyen --}}
    <h2>⏱️ Délai moyen de résolution (heures)</h2>
    <table>
        <thead><tr><th>Type de panne</th><th>Délai moyen (h)</th></tr></thead>
        <tbody>
            @foreach($delaiParType as $type => $heures)
            <tr><td>{{ $type }}</td><td>{{ $heures }}h</td></tr>
            @endforeach
        </tbody>
    </table>

    {{-- 3. Chambres problématiques --}}
    <h2>🏠 Chambres les plus problématiques (Top 5)</h2>
    <table>
        <thead><tr><th>Chambre</th><th>Nombre de pannes</th></tr></thead>
        <tbody>
            @foreach($chambresProblematiques as $c)
            <tr><td>{{ $c['chambre'] }}</td><td>{{ $c['total'] }}</td></tr>
            @endforeach
        </tbody>
    </table>

    {{-- 4. Évolution mensuelle --}}
    <h2>📈 Évolution des pannes par mois</h2>
    <table>
        <thead><tr><th>Mois</th><th>Nombre de pannes</th></tr></thead>
        <tbody>
            @foreach($pannesParMois as $mois => $total)
            <tr><td>{{ $mois }}</td><td>{{ $total }}</td></tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>