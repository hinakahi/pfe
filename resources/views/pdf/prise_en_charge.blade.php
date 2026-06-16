<!DOCTYPE html>
<html>
<head>
<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
    .header { text-align: center; margin-bottom: 15px; }
    .header p { margin: 2px 0; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
    table, th, td { border: 1px solid #333; padding: 4px; text-align: left; }
    th { background-color: #f0f0f0; text-align: center; }
    .signature-row { display: flex; justify-content: space-between; margin-top: 40px; }
</style>
</head>
<body>
    <div class="header">
        @if(isset($demande->chambreDemandee))
<p>N° de chambre : {{ $demande->chambreDemandee->numero ?? '___' }}</p>
@else
<p>N° de chambre : {{ $demande->chambre->numero ?? '___' }}</p>
@endif
        <p>{{ $demande->etudiante->name }}</p>
        <p style="font-weight: bold; margin-top: 10px;">
            Je soussigné(e), Melle {{ $demande->etudiante->name }},
            déclare être entièrement responsable du matériel commun avec toutes les occupantes
            du mobilier existant dans ma chambre et solidairement responsable du matériel
            mis à ma disposition. Je m'engage à restituer le matériel mis à ma disposition
            à la fin de l'année universitaire (ou en cas de mon départ), ou à régler les
            frais de dégradations constatés.
        </p>
    </div>

    <h4>Matériel individuel</h4>
    <table>
        <tr><th>N°</th><th>Désignation</th><th>Quantité</th><th>Observations</th></tr>
        @foreach($materielIndividuel as $i => $item)
        <tr>
            <td>{{ sprintf('%02d', $i+1) }}</td>
            <td>{{ $item['designation'] }}</td>
            <td>{{ $item['quantite'] ?? '' }}</td>
            <td>{{ $item['observation'] ?? '' }}</td>
        </tr>
        @endforeach
        @for($i = count($materielIndividuel); $i < count($materielIndividuel) + 3; $i++)
        <tr>
            <td>{{ sprintf('%02d', $i+1) }}</td>
            <td></td><td></td><td></td>
        </tr>
        @endfor
    </table>

    <h4>Matériel collectif</h4>
    <table>
        <tr><th>N°</th><th>Désignation</th><th>Quantité</th><th>Observations</th></tr>
        @foreach($materielCollectif as $i => $item)
        <tr>
            <td>{{ sprintf('%02d', $i+1) }}</td>
            <td>{{ $item['designation'] }}</td>
            <td>{{ $item['quantite'] ?? '' }}</td>
            <td>{{ $item['observation'] ?? '' }}</td>
        </tr>
        @endforeach
        @for($i = count($materielCollectif); $i < count($materielCollectif) + 3; $i++)
        <tr>
            <td>{{ sprintf('%02d', $i+1) }}</td>
            <td></td><td></td><td></td>
        </tr>
        @endfor
    </table>

    <p>Fait le {{ now()->format('d/m/Y') }}</p>

    <div class="signature-row">
        <div>Signature de l'étudiante :</div>
        <div>Cadre réservé au Responsable de lingerie</div>
    </div>

    <table style="margin-top: 10px;">
        <tr>
            <th>Entrée (cachet et signature)</th>
            <th>Sortie (cachet et signature)</th>
            <th>Observations</th>
        </tr>
        <tr>
            <td style="height: 60px;"></td>
            <td></td>
            <td></td>
        </tr>
    </table>
</body>
</html>