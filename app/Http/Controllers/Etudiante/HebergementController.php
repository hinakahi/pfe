<?php

namespace App\Http\Controllers\Etudiante;

use App\Http\Controllers\Controller;
use App\Models\Chambre;
use App\Models\DemandeRenouvellement;
use App\Models\DemandeChangement;
use App\Models\Periode;
use Illuminate\Http\Request;

class HebergementController extends Controller
{
    public function dashboard()
{
    $user = auth()->user();
    
    $maChambre = \App\Models\Chambre::where('etudiante_1', $user->matricule)
                 ->orWhere('etudiante_2', $user->matricule)
                 ->first();

    $derniereDemandeRenouvellement = \App\Models\DemandeRenouvellement::where('etudiante_id', $user->id)
                                    ->latest()->first();
    $derniereDemandeChangement = \App\Models\DemandeChangement::where('etudiante_id', $user->id)
                                ->latest()->first();

    $annonces = \App\Models\Annonce::latest()->take(3)->get();
    
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

        // ✅ Trouver la chambre actuelle de l'étudiante
        $maChambre = Chambre::where('etudiante_1', $user->name)
                     ->orWhere('etudiante_2', $user->name)
                     ->first();

        $periodeRenouvellement = Periode::where('type', 'renouvellement')
                                ->where('active', true)
                                ->where('date_debut', '<=', now())
                                ->where('date_fin', '>=', now())
                                ->first();

        $demandesRenouvellement = DemandeRenouvellement::where('etudiante_id', $user->id)
                                  ->latest()->get();

        return view('etudiante.hebergement.index', compact(
            'periodeRenouvellement',
            'demandesRenouvellement',
            'maChambre'  // ✅ ajouté
        ));
    }

    public function renouveler(Request $request)
    {
        $request->validate([
            'chambre_id'              => 'required|exists:chambres,id',
            'justificatif_scolarite'  => 'required|file|mimes:pdf,jpg,png|max:2048',
            'justificatif_paiement'   => 'required|file|mimes:pdf,jpg,png|max:2048',
        ]);

        $scolarite = $request->file('justificatif_scolarite')->store('justificatifs', 'public');
        $paiement  = $request->file('justificatif_paiement')->store('justificatifs', 'public');

        DemandeRenouvellement::create([
            'etudiante_id'           => auth()->id(),
            'chambre_id'             => $request->chambre_id,
            'justificatif_scolarite' => $scolarite,
            'justificatif_paiement'  => $paiement,
            'statut'                 => 'en_attente',
        ]);

        return redirect()->route('etudiante.hebergement.renouvellement')
                         ->with('success', 'Demande de renouvellement envoyée avec succès.');
    }

    public function showChangement()
    {
        $user = auth()->user();

        // ✅ Chambre actuelle de l'étudiante
        $maChambre = Chambre::where('etudiante_1', $user->name)
                     ->orWhere('etudiante_2', $user->name)
                     ->first();

        $chambresDisponibles = Chambre::whereNull('etudiante_1')->where('publiee', true)->get();

        $demandesChangement = DemandeChangement::where('etudiante_id', auth()->id())
                             ->latest()->get();

        return view('etudiante.hebergement.changement', compact(
            'maChambre',           // ✅ ajouté
            'chambresDisponibles',
            'demandesChangement'
        ));
    }

    public function demanderChangement(Request $request)
    {
        $request->validate([
            'chambre_actuelle_id'  => 'required|exists:chambres,id',
            'chambre_demandee_id'  => 'required|exists:chambres,id',
            'motif'                => 'required|string|max:500',
        ]);

        DemandeChangement::create([
            'etudiante_id'        => auth()->id(),
            'chambre_actuelle_id' => $request->chambre_actuelle_id,
            'chambre_demandee_id' => $request->chambre_demandee_id,
            'motif'               => $request->motif,
            'statut'              => 'en_attente',
        ]);

        return redirect()->route('etudiante.changement')
                         ->with('success', 'Demande de changement envoyée avec succès.');
    }
}