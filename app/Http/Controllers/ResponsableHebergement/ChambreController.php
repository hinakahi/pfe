<?php
namespace App\Http\Controllers\ResponsableHebergement;

use App\Http\Controllers\Controller;
use App\Models\Chambre;
use Illuminate\Http\Request;
use App\Imports\ChambresImport;
use Maatwebsite\Excel\Facades\Excel;

class ChambreController extends Controller
{
    // Dashboard
   public function dashboard()
{
    $stats = [
        'total'    => Chambre::count(),
        'libres'   => Chambre::where('statut', 'libre')->count(),
        'occupees' => Chambre::where('statut', 'occupee')->count(),
        'publiees' => Chambre::where('publiee', true)->count(),
    ];
    $dernieres = Chambre::latest()->take(5)->get();
    return view('hebergement.dashboard', compact('stats', 'dernieres'));
}

    // Liste toutes les chambres
public function index()
{
    $chambres = Chambre::orderBy('numero')->paginate(20);
    $stats = [
        'total'    => Chambre::count(),
        'occupees' => Chambre::where('statut', 'occupee')->count(),
        'libres'   => Chambre::where('statut', 'libre')->count(),
        'publiees' => Chambre::where('publiee', true)->count(),
    ];
    return view('hebergement.chambres.index', compact('chambres', 'stats'));
}
    // Formulaire ajout
    public function create()
    {
        return view('hebergement.chambres.create');
    }

    // Enregistrer une nouvelle chambre
    public function store(Request $request)
    {
       $request->validate([
    'numero'   => 'required|unique:chambres,numero',
    'type'     => 'required|in:individuelle,double',
    'bloc'     => 'required|string|max:10',
    'etage'    => 'required|integer|min:0',
    'capacite' => 'required|integer|min:1|max:2',
]);

Chambre::create($request->only('numero', 'type', 'bloc', 'etage', 'capacite'));

        return redirect()->route('hebergement.chambres.index')
                         ->with('success', 'Chambre ajoutée avec succès.');
    }

    // Supprimer une chambre
    public function destroy(Chambre $chambre)
    {
        $chambre->delete();
        return redirect()->route('hebergement.chambres.index')
                         ->with('success', 'Chambre supprimée.');
    }

    // Publier les chambres libres (les rendre visibles aux étudiantes)
    public function publierVides()
    {
        $nb = Chambre::where('statut', 'libre')->update(['publiee' => true]);
        return redirect()->route('hebergement.chambres.index')
                         ->with('success', "$nb chambre(s) publiée(s) avec succès.");
    }

    // Consulter uniquement les chambres vides publiées
    public function chambresVides()
    {
        $chambres = Chambre::where('statut', 'libre')->where('publiee', true)->get();
        return view('hebergement.chambres.vides', compact('chambres'));
    }
    // Afficher formulaire import
public function importForm()
{
    return view('hebergement.chambres.import');
}

// Traiter l'import
public function import(Request $request)
{
    $request->validate([
        'fichier' => 'required|mimes:xlsx,xls,csv'
    ]);

    Excel::import(new ChambresImport, $request->file('fichier'));

    return redirect()->route('hebergement.chambres.index')
                     ->with('success', 'Chambres importées avec succès !');
}
}