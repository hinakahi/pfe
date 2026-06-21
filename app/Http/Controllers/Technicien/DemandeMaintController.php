<?php

namespace App\Http\Controllers\Technicien;

use App\Http\Controllers\Controller;
use App\Models\Maintenance;
use App\Models\Materiel;
use App\Models\Stock;
use App\Models\User;
use App\Notifications\MaintenanceTermineeNotification;
use App\Notifications\NouvelleDemandeTechnicien;
use App\Notifications\StockEpuiseNotification;
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

        if ($request->statut && $request->statut !== 'tous') {
            if ($request->statut === 'non_traitees') {
                $query->whereIn('statut', ['en_attente', 'en_cours']);
            } else {
                $query->where('statut', $request->statut);
            }
        }

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
            'commentaire_technicien'   => 'nullable|string|max:500',
            'materiels'                => 'nullable|array',
            'materiels.*.stock_id'     => 'nullable|exists:stocks,id',
            'materiels.*.nom_materiel' => 'nullable|string|max:191',
            'materiels.*.quantite'     => 'nullable|integer|min:1',
            'stock_epuise'             => 'nullable|boolean',
        ]);

        $maintenance->update([
            'statut'                 => $request->statut,
            'technicien_id'          => auth()->id(),
            'commentaire_technicien' => $request->statut === 'en_cours'
                                        ? $request->commentaire_technicien
                                        : $maintenance->commentaire_technicien,
        ]);

        // Notifier les techniciens quand une demande est prise en cours
        if ($request->statut === 'en_cours') {
            $techniciens = User::where('role', 'technicien')->get();
            foreach ($techniciens as $tech) {
                $tech->notify(new NouvelleDemandeTechnicien($maintenance));
            }
        }

        // Si terminée : date de résolution + notification étudiante
        if ($request->statut === 'terminee') {
            $maintenance->update([
                'date_resolution'        => now(),
                'commentaire_technicien' => null,
            ]);
            $maintenance->etudiante->notify(new MaintenanceTermineeNotification($maintenance));
        }

        // Enregistrer le matériel utilisé + décrémenter le stock
        if ($request->filled('materiels')) {
            foreach ($request->materiels as $mat) {
                if (empty($mat['nom_materiel'])) continue;

                $quantite = $mat['quantite'] ?? 1;

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

                if ($stock) {
                    $stock->quantite = max(0, $stock->quantite - $quantite);
                    $stock->save();

                    // Notifier si stock sous le seuil minimum
                    if ($stock->quantite <= $stock->seuil_minimum) {
                        $techniciens = User::where('role', 'technicien')->get();
                        foreach ($techniciens as $tech) {
                            $tech->notify(new StockEpuiseNotification($stock));
                        }
                    }
                }
            }
        }

        return redirect()->route('technicien.demandes')
                         ->with('success', 'Demande mise à jour avec succès.');
    }
}