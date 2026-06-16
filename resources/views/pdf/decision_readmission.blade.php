<!DOCTYPE html>
<html>
<head>
<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
    .header { text-align: center; margin-bottom: 30px; }
    .content { line-height: 1.8; }
    table { width: 100%; margin-top: 20px; border-collapse: collapse; }
    table, th, td { border: 1px solid #333; padding: 6px; }
</style>
</head>
<body>
    <div class="header">
        <h2>Décision de Réadmission</h2>
        <p>Cité Universitaire — {{ now()->format('d/m/Y') }}</p>
    </div>

    <div class="content">
         <p>Nom : {{ $demande->etudiante->name }}</p>
         <p>Matricule : {{ $demande->etudiante->matricule }}</p>
         <p>Bloc : {{ $demande->chambre->bloc ?? '-' }}</p>
        <p>Type de demande : {{ $type === 'renouvellement' ? 'Renouvellement' : 'Changement' }}</p>
        <p>Chambre attribuée : {{ $demande->chambre->numero ?? $demande->chambre_id }}</p>
        <p>Décision : <strong>Admis(e)</strong></p>
        <p>Date d'effet : {{ $demande->updated_at->format('d/m/Y') }}</p>
    </div>

    <p style="margin-top: 60px;">Signature du Responsable d'Hébergement : ____________________</p>
</body>
</html>