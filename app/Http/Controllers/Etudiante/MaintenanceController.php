<?php

namespace App\Http\Controllers\Etudiante;

use App\Http\Controllers\Controller;
use App\Models\Maintenance;
use App\Models\Chambre;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function index()
    {
        $demandes = Maintenance::where('etudiante_id', auth()->id())
                   ->latest()->get();
        $chambres = Chambre::where('statut', 'occupee')->get();
        return view('etudiante.maintenance.index', compact('demandes', 'chambres'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'chambre_id'  => 'required|exists:chambres,id',
            'type'        => 'required|in:electricite,plomberie,menuiserie,autre',
            'description' => 'required|string|max:500',
            'urgence'     => 'required|in:normale,urgente',
        ]);

        Maintenance::create([
            'etudiante_id' => auth()->id(),
            'chambre_id'   => $request->chambre_id,
            'type'         => $request->type,
            'description'  => $request->description,
            'urgence'      => $request->urgence,
            'statut'       => 'en_attente',
        ]);

        return redirect()->route('etudiante.maintenance')
                         ->with('success', 'Panne signalée avec succès.');
    }

    public function destroy(Maintenance $maintenance)
    {
        if ($maintenance->etudiante_id !== auth()->id()) {
            abort(403);
        }

        if ($maintenance->statut !== 'en_attente') {
            return back()->with('error', 'Impossible d\'annuler une demande en cours.');
        }

        $maintenance->delete();
        return redirect()->route('etudiante.maintenance')
                         ->with('success', 'Demande annulée avec succès.');
    }
}