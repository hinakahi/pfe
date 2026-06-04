<?php

namespace App\Http\Controllers\Etudiante;

use App\Http\Controllers\Controller;
use App\Models\Reclamation;
use Illuminate\Http\Request;

class ReclamationController extends Controller
{
    public function index()
    {
        $reclamations = Reclamation::where('etudiante_id', auth()->id())
                       ->latest()->get();
        return view('etudiante.reclamations.index', compact('reclamations'));
    }

    public function create()
    {
        return view('etudiante.reclamations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'sujet'   => 'required|string|max:191',
            'message' => 'required|string|max:1000',
        ]);

        Reclamation::create([
            'etudiante_id' => auth()->id(),
            'sujet'        => $request->sujet,
            'message'      => $request->message,
            'statut'       => 'en_attente',
        ]);

        return redirect()->route('etudiante.reclamations.index')
                         ->with('success', 'Réclamation envoyée avec succès.');
    }

    public function destroy(Reclamation $reclamation)
    {
        if ($reclamation->etudiante_id !== auth()->id()) {
            abort(403);
        }

        if ($reclamation->statut !== 'en_attente') {
            return back()->with('error', 'Impossible de supprimer cette réclamation.');
        }

        $reclamation->delete();
        return redirect()->route('etudiante.reclamations.index')
                         ->with('success', 'Réclamation supprimée avec succès.');
    }
}