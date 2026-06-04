<?php

namespace App\Http\Controllers\ResponsableHebergement;

use App\Http\Controllers\Controller;
use App\Models\DemandeRenouvellement;
use Illuminate\Http\Request;

class RenouvellementController extends Controller
{
    public function index()
    {
        $demandes = DemandeRenouvellement::with('etudiante', 'chambre')
                   ->latest()->get();
        return view('hebergement.renouvellements.index', compact('demandes'));
    }

    public function valider(Request $request, DemandeRenouvellement $demande)
    {
        $demande->update([
            'statut'              => 'validee',
            'resp_hebergement_id' => auth()->id(),
        ]);

        $demande->chambre->update(['statut' => 'occupee']);

        return redirect()->route('hebergement.renouvellements')
                         ->with('success', 'Renouvellement validé avec succès.');
    }

    public function refuser(Request $request, DemandeRenouvellement $demande)
    {
        $request->validate([
            'motif_refus' => 'required|string|max:500',
        ]);

        $demande->update([
            'statut'              => 'refusee',
            'motif_refus'         => $request->motif_refus,
            'resp_hebergement_id' => auth()->id(),
        ]);

        return redirect()->route('hebergement.renouvellements')
                         ->with('success', 'Renouvellement refusé.');
    }
}