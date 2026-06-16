<?php

namespace App\Traits;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

trait GenerePdfDemande
{
    protected function genererDocumentsDemande($demande, string $type, array $materielIndividuel = [], array $materielCollectif = []): void
    {
        $pdfDecision = Pdf::loadView('pdf.decision_readmission', [
            'demande' => $demande,
            'type' => $type,
        ]);
        $nomDecision = 'decisions/decision_' . $type . '_' . $demande->id . '_' . time() . '.pdf';
        Storage::disk('public')->put($nomDecision, $pdfDecision->output());

        $pdfPriseEnCharge = Pdf::loadView('pdf.prise_en_charge', [
            'demande' => $demande,
            'materielIndividuel' => $materielIndividuel,
            'materielCollectif' => $materielCollectif,
        ]);
        $nomPriseEnCharge = 'prises_en_charge/pec_' . $type . '_' . $demande->id . '_' . time() . '.pdf';
        Storage::disk('public')->put($nomPriseEnCharge, $pdfPriseEnCharge->output());

        $demande->update([
            'decision_pdf' => $nomDecision,
            'prise_en_charge_pdf' => $nomPriseEnCharge,
        ]);
    }
}