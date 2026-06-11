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
public function index(Request $request)
{
    $user = auth()->user();

    $chambre = Chambre::where('etudiante_1', $user->matricule)
                      ->orWhere('etudiante_2', $user->matricule)
                      ->first();

    $query = Maintenance::with(['chambre', 'technicien'])
        ->where('etudiante_id', $user->id)
        ->latest();

    if ($request->filled('statut')) {
        $query->where('statut', $request->statut);
    }
    if ($request->filled('urgence')) {
        $query->where('urgence', $request->urgence);
    }
    if ($request->filled('periode')) {
        $query->where('created_at', '>=', now()->subDays((int) $request->periode));
    }

    $demandes = $query->paginate(10)->withQueryString();

    return view('etudiante.maintenance.index', compact('demandes', 'chambre'));
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

        $techniciens = User::where('role', 'technicien')->get();
        foreach ($techniciens as $technicien) {
            $technicien->notify(new NouvelleDemainteNotification($maintenance));
        }

        return redirect()->route('etudiante.maintenance.index')
                         ->with('success', 'Demande signalée avec succès.');
    }

    public function show(Maintenance $maintenance)
    {
        if ($maintenance->etudiante_id !== auth()->id()) {
            abort(403);
        }

        $maintenance->load(['chambre', 'technicien', 'materiels']);

        return view('etudiante.maintenance.show', compact('maintenance'));
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

        return redirect()->route('etudiante.maintenance.show', $maintenance)
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