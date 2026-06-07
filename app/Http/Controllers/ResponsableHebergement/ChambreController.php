<?php
namespace App\Http\Controllers\ResponsableHebergement;

use App\Http\Controllers\Controller;
use App\Models\Chambre;
use Illuminate\Http\Request;
use App\Imports\ChambresImport;
use Maatwebsite\Excel\Facades\Excel;

class ChambreController extends Controller
{
 public function dashboard()
{
    $toutes  = Chambre::all();

    $stats = [
        'total'    => $toutes->count(),
        'libres'   => $toutes->filter(fn($c) => $c->statut === 'libre')->count(),
        'occupees' => $toutes->filter(fn($c) => $c->statut === 'occupee')->count(),
        'publiees' => Chambre::where('publiee', true)->count(),
    ];

    $dernieres = Chambre::latest()->take(5)->get();

    return view('hebergement.dashboard', compact('stats', 'dernieres'));
}
public function index(Request $request)
{
    $query = Chambre::query();

    if ($request->filled('categorie')) {
        $query->where('type', $request->categorie);
    }

    if ($request->filled('search')) {
        $query->where(function($q) use ($request) {
            $q->where('numero', 'like', '%'.$request->search.'%')
              ->orWhere('bloc', 'like', '%'.$request->search.'%');
        });
    }

   if ($request->boolean('vides')) {
    $query->whereNull('etudiante_1');
}

    $chambres = $query->paginate(20)->withQueryString();

    
$stats = [
    'total'       => Chambre::count(),
    'occupees'    => Chambre::whereNotNull('etudiante_1')->whereNotNull('etudiante_2')->count(),
    'une_place'   => Chambre::where('type', 'Double')
                        ->whereNotNull('etudiante_1')
                        ->whereNull('etudiante_2')
                        ->count(),
    'disponibles' => Chambre::whereNull('etudiante_1')->count(),
];

    //  Ajoute 'stats' ici
    return view('hebergement.chambres.index', compact('chambres', 'stats'));
}

    public function create()
    {
        return view('hebergement.chambres.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'numero'      => 'required|unique:chambres,numero',
            'type'        => 'required|in:individuelle,double',
            'bloc'        => 'required|string|max:10',
            'etage'       => 'required|integer|min:0',
            'capacite'    => 'required|integer|min:1|max:2',
            'etudiante_1' => 'nullable|string|max:100',
            'etudiante_2' => 'nullable|string|max:100|required_if:type,double',
        ]);

        // etudiante_2 seulement pour les doubles
        $data = $request->only('numero', 'type', 'bloc', 'etage', 'capacite', 'etudiante_1');
        if ($request->type === 'double') {
            $data['etudiante_2'] = $request->etudiante_2;
        }

        Chambre::create($data);

        return redirect()->route('hebergement.chambres.index')
                         ->with('success', 'Chambre ajoutée avec succès.');
    }

  public function edit(Chambre $chambre)
{
    $etudiantes = \App\Models\User::where('role', 'etudiante')
                  ->select('id', 'name', 'matricule')
                  ->get();
    return view('hebergement.chambres.edit', compact('chambre', 'etudiantes'));
}

    public function update(Request $request, Chambre $chambre)
    {
        $request->validate([
            'etudiante_1' => 'nullable|string|max:100',
            'etudiante_2' => 'nullable|string|max:100',
        ]);

        $chambre->update([
            'etudiante_1' => $request->etudiante_1,
            'etudiante_2' => $chambre->type === 'double' ? $request->etudiante_2 : null,
        ]);

        return redirect()->route('hebergement.chambres.index')
                         ->with('success', 'Chambre mise à jour.');
    }

    public function destroy(Chambre $chambre)
    {
        $chambre->delete();
        return redirect()->route('hebergement.chambres.index')
                         ->with('success', 'Chambre supprimée.');
    }

    // Publie toutes les chambres avec au moins une place libre
    public function publierVides()
    {
        $nb = Chambre::where(function($q) {
            $q->whereNull('etudiante_1')
              ->orWhereNull('etudiante_2');
        })->update(['publiee' => true]);

        return redirect()->route('hebergement.chambres.index')
                         ->with('success', "$nb chambre(s) publiée(s) avec succès.");
    }

    public function chambresVides()
    {
        $chambres = Chambre::where('publiee', true)
            ->where(function($q) {
                $q->whereNull('etudiante_1')
                  ->orWhereNull('etudiante_2');
            })->get();

        return view('hebergement.chambres.vides', compact('chambres'));
    }

    public function importForm()
    {
        return view('hebergement.chambres.import');
    }

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