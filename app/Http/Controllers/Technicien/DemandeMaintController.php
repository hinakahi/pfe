<?php

namespace App\Http\Controllers\Technicien;

use App\Http\Controllers\Controller;
use App\Models\Maintenance;
use App\Models\Materiel;
use App\Models\Stock;
use App\Notifications\MaintenanceTermineeNotification;
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
               ->orderByRaw("FIELD(urgence, 'urgente', 'normale')")
               ->orderByRaw("FIELD(statut, 'en_attente', 'en_cours')")
               ->latest()->take(5)->get();

    return view('technicien.dashboard', compact('stats', 'demandes'));
}

  public function index(Request $request)
{
    $query = Maintenance::with('etudiante', 'chambre', 'materiels')
               ->orderByRaw("FIELD(urgence, 'urgente', 'normale')")
               ->orderByRaw("FIELD(statut, 'en_attente', 'en_cours', 'terminee')")
               ->latest();

    // Filtre par statut
    if ($request->statut && $request->statut !== 'tous') {
        $query->where('statut', $request->statut);
    }

    // Filtre par type
    if ($request->type && $request->type !== 'tous') {
        $query->where('type', $request->type);
    }

    $demandes = $query->get();

    return view('technicien.demandes.index', compact('demandes'));
}

    public function show(Maintenance $maintenance)
    {
        $maintenance->load('etudiante', 'chambre', 'materiels');
        $stocks = Stock::orderBy('designation')->get();
        return view('technicien.demandes.show', compact('maintenance', 'stocks'));
    }

    public function traiter(Request $request, Maintenance $maintenance)
    {
        $request->validate([
            'statut'                   => 'required|in:en_attente,en_cours,terminee',
            'materiels'                => 'nullable|array',
            'materiels.*.stock_id'     => 'nullable|exists:stocks,id',
            'materiels.*.nom_materiel' => 'nullable|string|max:191',
            'materiels.*.quantite'     => 'nullable|integer|min:1',
            'stock_epuise'             => 'nullable|boolean',
        ]);

        // Mettre à jour le statut
        $maintenance->update([
            'statut'        => $request->statut,
            'technicien_id' => auth()->id(),
        ]);

        // Si terminée : date de résolution + notification
        if ($request->statut === 'terminee') {
            $maintenance->update(['date_resolution' => now()]);
            $maintenance->etudiante->notify(new MaintenanceTermineeNotification($maintenance));
        }

        // Enregistrer le matériel utilisé + décrémenter le stock
        if ($request->filled('materiels')) {
            foreach ($request->materiels as $mat) {

                // Skip si nom vide
                if (empty($mat['nom_materiel'])) continue;

                $quantite = $mat['quantite'] ?? 1;

                // Chercher le stock correspondant
                $stock = isset($mat['stock_id'])
                    ? Stock::find($mat['stock_id'])
                    : Stock::where('designation', $mat['nom_materiel'])->first();

                Materiel::create([
                    'maintenance_id'       => $maintenance->id,
                    'nom_materiel'         => $mat['nom_materiel'],
                    'quantite'             => $quantite,
                    'stock_epuise'         => $request->boolean('stock_epuise'),
                    'description_incident' => null,
                ]);

                // Décrémenter le stock si trouvé
                if ($stock) {
                    $stock->quantite = max(0, $stock->quantite - $quantite);
                    $stock->save();
                }
            }
        }

        return redirect()->route('technicien.demandes')
                         ->with('success', 'Demande mise à jour avec succès.');
    }
}