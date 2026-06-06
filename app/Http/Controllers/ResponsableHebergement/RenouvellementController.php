<?php
namespace App\Http\Controllers\ResponsableHebergement;

use App\Http\Controllers\Controller;
use App\Models\DemandeRenouvellement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RenouvellementController extends Controller
{
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

        return back()->with('success', 'Renouvellement validé avec succès.');
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

        return back()->with('success', 'Renouvellement refusé.');
    }
}