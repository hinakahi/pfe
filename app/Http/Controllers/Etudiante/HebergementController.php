<?php

namespace App\Http\Controllers\Etudiante;

use App\Http\Controllers\Controller;
use App\Models\Chambre;
use App\Models\DemandeRenouvellement;
use App\Models\DemandeChangement;
use App\Models\Periode;
use App\Models\Annonce;
use App\Models\User;
use App\Notifications\NouvelleDemandeChambre;
use Illuminate\Http\Request;

class HebergementController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        $maChambre = Chambre::where('etudiante_1', $user->matricule)
                     ->orWhere('etudiante_2', $user->matricule)
                     ->first();

        $derniereDemandeRenouvellement = DemandeRenouvellement::where('etudiante_id', $user->id)
                                        ->latest()->first();
        $derniereDemandeChangement = DemandeChangement::where('etudiante_id', $user->id)
                                    ->latest()->first();

        $annonces = Annonce::latest()->take(3)->get();
        $notifications = $user->notifications()->take(5)->get();

        return view('etudiante.dashboard', compact(
            'maChambre',
            'derniereDemandeRenouvellement',
            'derniereDemandeChangement',
            'annonces',
            'notifications'
        ));
    }

    public function index()
    {
        $user = auth()->user();

        $maChambre = Chambre::where('etudiante_1', $user->matricule)
                     ->orWhere('etudiante_2', $user->matricule)
                     ->first();

        // ✅ Les deux périodes récupérées
        $periodeRenouvellement = Periode::where('type', 'renouvellement')
                                ->where('active', true)
                                ->where('date_debut', '<=', now())
                                ->where('date_fin', '>=', now())
                                ->first();

        $periodeChangement = Periode::where('type', 'changement')
                            ->where('active', true)
                            ->where('date_debut', '<=', now())
                            ->where('date_fin', '>=', now())
                            ->first();

        $demandesRenouvellement = DemandeRenouvellement::where('etudiante_id', $user->id)
                                  ->latest()->get();

        $demandesChangement = DemandeChangement::where('etudiante_id', $user->id)
                              ->latest()->get();

        return view('etudiante.hebergement.index', compact(
            'maChambre',
            'periodeRenouvellement',
            'periodeChangement',        // ✅ ajouté
            'demandesRenouvellement',
            'demandesChangement'
        ));
    }
    public function showRenouvellement()
{
    $user = auth()->user();

    $maChambre = Chambre::where('etudiante_1', $user->matricule)
                 ->orWhere('etudiante_2', $user->matricule)
                 ->first();

    $periodeRenouvellement = Periode::where('type', 'renouvellement')
                            ->where('active', true)
                            ->where('date_debut', '<=', now())
                            ->where('date_fin', '>=', now())
                            ->first();

    $demandesRenouvellement = DemandeRenouvellement::where('etudiante_id', $user->id)
                              ->latest()->get();

    return view('etudiante.hebergement.renouvellement', compact(
        'maChambre',
        'periodeRenouvellement',
        'demandesRenouvellement'
    ));
}

   public function renouveler(Request $request)
{
    $periode = Periode::where('type', 'renouvellement')
              ->where('active', true)
              ->where('date_debut', '<=', now())
              ->where('date_fin', '>=', now())
              ->first();

    if (!$periode) {
        return back()->with('error', 'La période de renouvellement est fermée.');
    }

    // ✅ Bloquer les doublons
    $dejaEnCours = DemandeRenouvellement::where('etudiante_id', auth()->id())
        ->whereIn('statut', ['en_attente', 'validee'])
        ->exists();

    if ($dejaEnCours) {
        return back()->with('error', 'Vous avez déjà une demande en cours.');
    }

    $request->validate([
        'chambre_id'             => 'required|exists:chambres,id',
        'justificatif_scolarite' => 'required|file|mimes:pdf,jpg,png|max:2048',
        'justificatif_paiement'  => 'required|file|mimes:pdf,jpg,png|max:2048',
    ]);

    $scolarite = $request->file('justificatif_scolarite')->store('justificatifs', 'public');
    $paiement  = $request->file('justificatif_paiement')->store('justificatifs', 'public');
    $chambre   = Chambre::find($request->chambre_id);

    DemandeRenouvellement::create([
        'etudiante_id'           => auth()->id(),
        'chambre_id'             => $request->chambre_id,
        'justificatif_scolarite' => $scolarite,
        'justificatif_paiement'  => $paiement,
        'statut'                 => 'en_attente',
    ]);

    $responsable = User::where('role', 'resp_hebergement')->first();
    if ($responsable) {
        $responsable->notify(new NouvelleDemandeChambre(
            'renouvellement',
            auth()->user()->name,
            $chambre->numero ?? '-'
        ));
    }

    return redirect()->route('etudiante.hebergement.renouvellement')
                     ->with('success', 'Demande envoyée avec succès.');
}

// ✅ Modifier si refusée
public function modifierRenouvellement(Request $request, DemandeRenouvellement $demande)
{
    if ($demande->etudiante_id !== auth()->id()) abort(403);

    if ($demande->statut !== 'refusee') {
        return back()->with('error', 'Modification impossible.');
    }

    $request->validate([
        'justificatif_scolarite' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        'justificatif_paiement'  => 'nullable|file|mimes:pdf,jpg,png|max:2048',
    ]);

    $data = ['statut' => 'en_attente', 'motif_refus' => null];

    if ($request->hasFile('justificatif_scolarite')) {
        $data['justificatif_scolarite'] = $request->file('justificatif_scolarite')
            ->store('justificatifs', 'public');
    }

    if ($request->hasFile('justificatif_paiement')) {
        $data['justificatif_paiement'] = $request->file('justificatif_paiement')
            ->store('justificatifs', 'public');
    }

    $demande->update($data);

    return back()->with('success', 'Demande corrigée et renvoyée.');
}

    public function showChangement()
    {
        $user = auth()->user();

        $maChambre = Chambre::where('etudiante_1', $user->matricule)
                     ->orWhere('etudiante_2', $user->matricule)
                     ->first();

        // ✅ Vérification période pour afficher ou bloquer la page
        $periodeChangement = Periode::where('type', 'changement')
                            ->where('active', true)
                            ->where('date_debut', '<=', now())
                            ->where('date_fin', '>=', now())
                            ->first();

        $chambresDisponibles = Chambre::whereNull('etudiante_1')
                               ->where('publiee', true)
                               ->paginate(15);

        $demandesChangement = DemandeChangement::where('etudiante_id', auth()->id())
                             ->latest()->get();

        return view('etudiante.hebergement.changement', compact(
            'maChambre',
            'periodeChangement',        // ✅ ajouté
            'chambresDisponibles',
            'demandesChangement'
        ));
    }

    public function demanderChangement(Request $request)
    {
        // ✅ Vérification période changement
        $periode = Periode::where('type', 'changement')
                  ->where('active', true)
                  ->where('date_debut', '<=', now())
                  ->where('date_fin', '>=', now())
                  ->first();

        if (!$periode) {
            return back()->with('error', 'La période de changement est fermée.');
        }

        $rules = [
            'chambre_actuelle_id' => 'required|exists:chambres,id',
            'chambre_demandee_id' => 'required|exists:chambres,id',
            'motif'               => 'required|string|max:500',
        ];

        $chambre = Chambre::find($request->chambre_demandee_id);
        if ($chambre && $chambre->type === 'individuelle') {
            $rules['justificatif'] = 'required|file|mimes:pdf|max:5120';
        }

        $request->validate($rules);

        $data = [
            'etudiante_id'        => auth()->id(),
            'chambre_actuelle_id' => $request->chambre_actuelle_id,
            'chambre_demandee_id' => $request->chambre_demandee_id,
            'motif'               => $request->motif,
            'statut'              => 'en_attente',
        ];

        if ($request->hasFile('justificatif')) {
            $data['justificatif'] = $request->file('justificatif')->store('justificatifs', 'public');
        }

        DemandeChangement::create($data);

        $chambreActuelle = Chambre::find($request->chambre_actuelle_id);
        $responsable = User::where('role', 'resp_hebergement')->first();
        if ($responsable) {
            $responsable->notify(new NouvelleDemandeChambre(
                'changement',
                auth()->user()->name,
                $chambreActuelle->numero ?? '-'
            ));
        }

        return redirect()->route('etudiante.changement')
                         ->with('success', 'Demande de changement envoyée avec succès.');
    }
}