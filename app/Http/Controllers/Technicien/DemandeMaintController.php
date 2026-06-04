<?php

namespace App\Http\Controllers\Technicien;

use App\Http\Controllers\Controller;
use App\Models\Maintenance;
use App\Models\Materiel;
use Illuminate\Http\Request;

class DemandeMaintController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'en_attente' => Maintenance::where('statut', 'en_attente')->count(),
            'en_cours'   => Maintenance::where('statut', 'en_cours')->count(),
            'terminees'  => Maintenance::where('statut', 'terminee')->count(),
            'urgentes'   => Maintenance::where('urgence', 'urgente')
                           ->where('statut', '!=', 'terminee')->count(),
        ];

        $demandes = Maintenance::with('etudiante', 'chambre')
                   ->where('statut', '!=', 'terminee')
                   ->latest()->take(5)->get();

        return view('technicien.dashboard', compact('stats', 'demandes'));
    }

    public function index()
    {
        $demandes = Maintenance::with('etudiante', 'chambre')
                   ->latest()->get();
        return view('technicien.demandes.index', compact('demandes'));
    }

    public function show(Maintenance $maintenance)
    {
        $maintenance->load('etudiante', 'chambre', 'materiels');
        return view('technicien.demandes.show', compact('maintenance'));
    }

    public function traiter(Request $request, Maintenance $maintenance)
    {
        $request->validate([
            'statut'       => 'required|in:en_cours,terminee',
            'materiels'    => 'nullable|array',
            'materiels.*.nom_materiel' => 'required_with:materiels|string|max:191',
            'materiels.*.quantite'     => 'required_with:materiels|integer|min:1',
            'stock_epuise'             => 'nullable|boolean',
            'description_incident'     => 'nullable|string|max:500',
        ]);

        $maintenance->update([
            'statut'       => $request->statut,
            'technicien_id' => auth()->id(),
        ]);

        if ($request->has('materiels')) {
            foreach ($request->materiels as $mat) {
                Materiel::create([
                    'maintenance_id'      => $maintenance->id,
                    'nom_materiel'        => $mat['nom_materiel'],
                    'quantite'            => $mat['quantite'],
                    'stock_epuise'        => $request->stock_epuise ?? false,
                    'description_incident' => $request->description_incident,
                ]);
            }
        }

        return redirect()->route('technicien.demandes')
                         ->with('success', 'Demande mise à jour avec succès.');
    }
}