<?php
namespace App\Http\Controllers\ResponsableHebergement;

use App\Http\Controllers\Controller;
use App\Models\DemandeRenouvellement;
use App\Notifications\RenouvellementTraite;
use App\Traits\GenerePdfDemande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RenouvellementController extends Controller
{   use GenerePdfDemande;
    public function index()
    {
        $enAttente = DemandeRenouvellement::with(['etudiante', 'chambre'])
            ->where('statut', 'en_attente')
            ->latest()->get();

        $traitees = DemandeRenouvellement::with(['etudiante', 'chambre'])
            ->whereIn('statut', ['validee', 'refusee'])
            ->latest()->take(10)->get();

        return view('hebergement.renouvellements.index', compact('enAttente', 'traitees'));
    }

    public function valider(Request $request, DemandeRenouvellement $demande)
{   
    $demande->update([
        'statut'              => 'validee',
        'resp_hebergement_id' => Auth::id(),
    ]);

    $materielIndividuel = $request->input('individuel', []);
    $materielCollectif = $request->input('collectif', []);

    $this->genererDocumentsDemande($demande, 'renouvellement', $materielIndividuel, $materielCollectif);

    // Notifier l'étudiante
    $demande->etudiante->notify(new RenouvellementTraite('validee'));

    return back()->with('success', 'Renouvellement validé avec succès. Documents générés.');
}
    public function modifierPriseEnCharge(Request $request, DemandeRenouvellement $demande)
{
    $materielIndividuel = $request->input('individuel', []);
    $materielCollectif = $request->input('collectif', []);

    $this->regenererPriseEnCharge($demande, 'renouvellement', $materielIndividuel, $materielCollectif);

    return back()->with('success', 'Prise en charge mise à jour avec succès.');
}
    public function refuser(Request $request, DemandeRenouvellement $demande)
    {
        $request->validate([
            'motif_refus' => 'required|string|min:10',
        ]);

        $demande->update([
            'statut'              => 'refusee',
            'motif_refus'         => $request->motif_refus,
            'resp_hebergement_id' => Auth::id(),
        ]);

        // Notifier l'étudiante
        $demande->etudiante->notify(new RenouvellementTraite('refusee', $request->motif_refus));

        return back()->with('success', 'Renouvellement refusé.');
    }
}