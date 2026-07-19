<?php
namespace App\Http\Controllers\Etudiante;

use App\Http\Controllers\Controller;
use App\Models\Maintenance;
use App\Models\Chambre;
use App\Models\User;
use App\Notifications\NouvelleDemainteNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'type_lieu'    => 'required|in:chambre,commun',
            'chambre_id'   => 'required_if:type_lieu,chambre|nullable|exists:chambres,id',
            'lieu_type'    => 'required_if:type_lieu,commun|nullable|string|max:100',
            'lieu_bloc'    => 'nullable|string|max:50',
            'lieu_etage'   => 'nullable|string|max:50',
            'lieu_autre'   => 'required_if:lieu_type,Autre|nullable|string|max:255',
            'type'         => 'required|in:electricite,plomberie,menuiserie,autre',
            'description'  => 'required|string|max:500',
            'urgence'      => 'required|in:normale,urgente',
            'photo'        => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('maintenances', 'public');
        }

        // Construction du lieu commun à partir des morceaux du formulaire
        $lieuCommun = null;
        if ($request->type_lieu === 'commun') {
            $lieu = $request->lieu_type === 'Autre' ? $request->lieu_autre : $request->lieu_type;
            $details = array_filter([
                $request->filled('lieu_bloc') ? 'Bloc ' . $request->lieu_bloc : null,
                $request->filled('lieu_etage') ? 'Étage ' . $request->lieu_etage : null,
            ]);
            $lieuCommun = $lieu . (count($details) ? ' — ' . implode(', ', $details) : '');
        }

        $maintenance = Maintenance::create([
            'etudiante_id' => auth()->id(),
            'chambre_id'   => $request->type_lieu === 'chambre' ? $request->chambre_id : null,
            'lieu_commun'  => $lieuCommun,
            'type'         => $request->type,
            'description'  => $request->description,
            'urgence'      => $request->urgence,
            'photo'        => $photoPath,
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

        $user = auth()->user();

        $chambre = Chambre::where('etudiante_1', $user->matricule)
                          ->orWhere('etudiante_2', $user->matricule)
                          ->first();

        return view('etudiante.maintenance.edit', compact('maintenance', 'chambre'));
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
            'type_lieu'    => 'required|in:chambre,commun',
            'chambre_id'   => 'required_if:type_lieu,chambre|nullable|exists:chambres,id',
            'lieu_type'    => 'required_if:type_lieu,commun|nullable|string|max:100',
            'lieu_bloc'    => 'nullable|string|max:50',
            'lieu_etage'   => 'nullable|string|max:50',
            'lieu_autre'   => 'required_if:lieu_type,Autre|nullable|string|max:255',
            'type'         => 'required|in:electricite,plomberie,menuiserie,autre',
            'description'  => 'required|string|max:500',
            'urgence'      => 'required|in:normale,urgente',
            'photo'        => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $lieuCommun = null;
        if ($request->type_lieu === 'commun') {
            $lieu = $request->lieu_type === 'Autre' ? $request->lieu_autre : $request->lieu_type;
            $details = array_filter([
                $request->filled('lieu_bloc') ? 'Bloc ' . $request->lieu_bloc : null,
                $request->filled('lieu_etage') ? 'Étage ' . $request->lieu_etage : null,
            ]);
            $lieuCommun = $lieu . (count($details) ? ' — ' . implode(', ', $details) : '');
        }

        // Gestion de la nouvelle photo (si l'étudiante en envoie une nouvelle)
        $photoPath = $maintenance->photo; // garde l'ancienne photo par défaut
        if ($request->hasFile('photo')) {
            // Supprime l'ancienne photo du stockage si elle existe
            if ($maintenance->photo) {
                Storage::disk('public')->delete($maintenance->photo);
            }
            $photoPath = $request->file('photo')->store('maintenances', 'public');
        }

        $maintenance->update([
            'chambre_id'  => $request->type_lieu === 'chambre' ? $request->chambre_id : null,
            'lieu_commun' => $lieuCommun,
            'type'        => $request->type,
            'description' => $request->description,
            'urgence'     => $request->urgence,
            'photo'       => $photoPath,
        ]);

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