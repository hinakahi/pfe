<?php

namespace App\Http\Controllers\ResponsableHebergement;

use App\Http\Controllers\Controller;
use App\Models\Chambre;
use App\Models\Maintenance;
use App\Models\DemandeRenouvellement;
use Illuminate\Http\Request;

class ChambreController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_chambres'       => Chambre::count(),
            'chambres_disponibles' => Chambre::where('statut', 'disponible')->count(),
            'chambres_occupees'    => Chambre::where('statut', 'occupee')->count(),
            'renouvellements'      => DemandeRenouvellement::where('statut', 'en_attente')->count(),
            'pannes'               => Maintenance::where('statut', 'en_attente')->count(),
        ];
        return view('hebergement.dashboard', compact('stats'));
    }

    public function index()
    {
        $chambres = Chambre::latest()->get();
        return view('hebergement.chambres.index', compact('chambres'));
    }

    public function create()
    {
        return view('hebergement.chambres.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'numero'   => 'required|string|max:191',
            'bloc'     => 'required|string|max:191',
            'etage'    => 'required|integer|min:0',
            'capacite' => 'required|integer|in:1,2',
            'statut'   => 'required|in:disponible,occupee,maintenance',
        ]);

        Chambre::create([
            'numero'              => $request->numero,
            'bloc'                => $request->bloc,
            'etage'               => $request->etage,
            'capacite'            => $request->capacite,
            'statut'              => $request->statut,
            'resp_hebergement_id' => auth()->id(),
        ]);

        return redirect()->route('hebergement.chambres.index')
                         ->with('success', 'Chambre ajoutée avec succès.');
    }

    public function edit(Chambre $chambre)
    {
        return view('hebergement.chambres.edit', compact('chambre'));
    }

    public function update(Request $request, Chambre $chambre)
    {
        $request->validate([
            'numero'   => 'required|string|max:191',
            'bloc'     => 'required|string|max:191',
            'etage'    => 'required|integer|min:0',
            'capacite' => 'required|integer|in:1,2',
            'statut'   => 'required|in:disponible,occupee,maintenance',
        ]);

        $chambre->update($request->only('numero', 'bloc', 'etage', 'capacite', 'statut'));

        return redirect()->route('hebergement.chambres.index')
                         ->with('success', 'Chambre modifiée avec succès.');
    }

    public function destroy(Chambre $chambre)
    {
        $chambre->delete();
        return redirect()->route('hebergement.chambres.index')
                         ->with('success', 'Chambre supprimée avec succès.');
    }
}