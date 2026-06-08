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

        $etudiante = $demande->etudiante;

        // ✅ Libérer l'ancienne chambre
        if ($demande->chambreActuelle) {
            $chambreActuelle = $demande->chambreActuelle;

            if ($chambreActuelle->etudiante_1 === $etudiante->matricule) {
                $chambreActuelle->update([
                    'etudiante_1' => null,
                    'statut'      => 'libre',
                ]);
            } elseif ($chambreActuelle->etudiante_2 === $etudiante->matricule) {
                $chambreActuelle->update([
                    'etudiante_2' => null,
                    'statut'      => 'libre',
                ]);
            }
        }

        // ✅ Affecter la nouvelle chambre
        if ($demande->chambreDemandee) {
            $chambreNouvelle = $demande->chambreDemandee;

            if (is_null($chambreNouvelle->etudiante_1)) {
                $chambreNouvelle->update([
                    'etudiante_1' => $etudiante->matricule,
                    'statut'      => 'occupee',
                ]);
            } elseif (is_null($chambreNouvelle->etudiante_2)) {
                $chambreNouvelle->update([
                    'etudiante_2' => $etudiante->matricule,
                    'statut'      => 'occupee',
                ]);
            }
        }

        $demande->update([
            'statut'              => 'acceptee',
            'resp_hebergement_id' => Auth::id(),
        ]);

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

        $demande->etudiante->notify(new ChangementTraite('refusee', $request->motif_refus));

        return back()->with('success', 'Demande de changement refusée.');
    }
}