<?php

namespace App\Http\Controllers\ResponsableHebergement;

use App\Http\Controllers\Controller;
use App\Models\DemandeChangement;
use Illuminate\Http\Request;

class ChangementController extends Controller
{
    public function index()
    {
        $demandes = DemandeChangement::with('etudiante', 'chambreActuelle', 'chambreDemandee')
                   ->latest()->get();
        return view('hebergement.changements.index', compact('demandes'));
    }

    public function accepter(Request $request, DemandeChangement $demande)
    {
        if (!$demande->chambreDemandee || !$demande->chambreDemandee->isDisponible()) {
            return back()->with('error', 'La chambre demandée n\'est plus disponible.');
        }

        $demande->chambreActuelle->update(['statut' => 'disponible']);
        $demande->chambreDemandee->update(['statut' => 'occupee']);

        $demande->update([
            'statut'              => 'acceptee',
            'resp_hebergement_id' => auth()->id(),
        ]);

        return redirect()->route('hebergement.changements')
                         ->with('success', 'Changement de chambre accepté.');
    }

    public function refuser(Request $request, DemandeChangement $demande)
    {
        $request->validate([
            'motif_refus' => 'required|string|max:500',
        ]);

        $demande->update([
            'statut'              => 'refusee',
            'motif_refus'         => $request->motif_refus,
            'resp_hebergement_id' => auth()->id(),
        ]);

        return redirect()->route('hebergement.changements')
                         ->with('success', 'Demande de changement refusée.');
    }
}