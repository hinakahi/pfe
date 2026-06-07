<?php
namespace App\Http\Controllers\Etudiante;

use App\Http\Controllers\Controller;
use App\Models\Maintenance;
use App\Models\Chambre;
use App\Models\User;
use App\Notifications\NouvelleDemainteNotification;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function index()
    {
        $demandes = Maintenance::where('etudiante_id', auth()->id())
                   ->latest()->get();
        $chambres = Chambre::whereNotNull('etudiante_1')->get();
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

        $maintenance = Maintenance::create([
            'etudiante_id' => auth()->id(),
            'chambre_id'   => $request->chambre_id,
            'type'         => $request->type,
            'description'  => $request->description,
            'urgence'      => $request->urgence,
            'statut'       => 'en_attente',
        ]);

        // Notifier tous les techniciens
        $techniciens = User::where('role', 'technicien')->get();
        foreach ($techniciens as $technicien) {
            $technicien->notify(new NouvelleDemainteNotification($maintenance));
        }

        return redirect()->route('etudiante.maintenance.index')
                         ->with('success', 'Demande signalée avec succès.');
    }
    public function edit(Maintenance $maintenance)
{
    if ($maintenance->etudiante_id !== auth()->id()) {
        abort(403);
    }
    if ($maintenance->statut !== 'en_attente') {
        return redirect()->route('etudiante.maintenance.index')
                         ->with('error', 'Impossible de modifier une demande en cours ou terminée.');
    }
    $chambres = Chambre::all();
    return view('etudiante.maintenance.edit', compact('maintenance', 'chambres'));
}

public function update(Request $request, Maintenance $maintenance)
{
    if ($maintenance->etudiante_id !== auth()->id()) {
        abort(403);
    }
    if ($maintenance->statut !== 'en_attente') {
        return redirect()->route('etudiante.maintenance.index')
                         ->with('error', 'Impossible de modifier une demande en cours ou terminée.');
    }

    $request->validate([
        'chambre_id'  => 'required|exists:chambres,id',
        'type'        => 'required|in:electricite,plomberie,menuiserie,autre',
        'description' => 'required|string|max:500',
        'urgence'     => 'required|in:normale,urgente',
    ]);

    $maintenance->update($request->only('chambre_id', 'type', 'description', 'urgence'));

    return redirect()->route('etudiante.maintenance.index')
                     ->with('success', 'Demande modifiée avec succès.');
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
        return redirect()->route('etudiante.maintenance.index')
                         ->with('success', 'Demande annulée avec succès.');
    }
}