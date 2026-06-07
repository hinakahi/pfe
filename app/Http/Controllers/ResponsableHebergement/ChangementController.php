<?php
namespace App\Http\Controllers\ResponsableHebergement;

use App\Http\Controllers\Controller;
use App\Models\DemandeChangement;
use App\Notifications\ChangementTraite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChangementController extends Controller
{
    public function index()
    {
        $enAttente = DemandeChangement::with(['etudiante', 'chambreActuelle', 'chambreDemandee'])
            ->where('statut', 'en_attente')
            ->latest()->get();

        $traitees = DemandeChangement::with(['etudiante', 'chambreActuelle', 'chambreDemandee'])
            ->whereIn('statut', ['acceptee', 'refusee'])
            ->latest()->take(10)->get();

        return view('hebergement.changements.index', compact('enAttente', 'traitees'));
    }

    public function accepter(Request $request, DemandeChangement $demande)
    {
        if ($demande->chambreDemandee && $demande->chambreDemandee->statut !== 'libre') {
            return back()->with('error', 'La chambre demandée n\'est plus disponible.');
        }

        if ($demande->chambreActuelle) {
            $demande->chambreActuelle->update(['statut' => 'libre']);
        }

        if ($demande->chambreDemandee) {
            $demande->chambreDemandee->update(['statut' => 'occupee']);
        }

        $demande->update([
            'statut'              => 'acceptee',
            'resp_hebergement_id' => Auth::id(),
        ]);

        // Notifier l'étudiante
        $demande->etudiante->notify(new ChangementTraite('acceptee'));

        return back()->with('success', 'Changement de chambre accepté avec succès.');
    }

    public function refuser(Request $request, DemandeChangement $demande)
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
        $demande->etudiante->notify(new ChangementTraite('refusee', $request->motif_refus));

        return back()->with('success', 'Demande de changement refusée.');
    }
}