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
        $stats = [
        'total'       => Chambre::count(),
        'disponibles' => Chambre::libres()->count(),
        'occupees'    => Chambre::occupees()->count(),
        'une_place'   => Chambre::partielles()->count(),
        'publiees'    => Chambre::publiees()->count(),
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
            $query->where(function ($q) use ($request) {
                $q->where('numero', 'like', '%' . $request->search . '%')
                  ->orWhere('bloc', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->boolean('vides')) {
            $ids = Chambre::libres()->pluck('id')
                    ->merge(Chambre::partielles()->pluck('id'));
            $query->whereIn('id', $ids);
        }

        if ($request->filled('statut')) {
            match ($request->statut) {
                'occupee'   => $query->occupees(),
                'partielle' => $query->partielles(),
                'libre'     => $query->libres(),
                default     => null,
            };
        }
        $chambres = $query->paginate(20)->withQueryString();

            $stats = [
            'total'       => Chambre::count(),
            'occupees'    => Chambre::occupees()->count(),
            'une_place'   => Chambre::partielles()->count(),
            'disponibles' => Chambre::libres()->count(),
        ];

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
            'etudiante_2' => 'nullable|string|max:100',
        ]);

        $data = $request->only('numero', 'type', 'bloc', 'etage', 'capacite', 'etudiante_1');

        if ($request->type === 'double') {
            $data['etudiante_2'] = $request->etudiante_2;
        }

        // Calculer le statut initial
        $occupied = collect([$data['etudiante_1'] ?? null, $data['etudiante_2'] ?? null])
                    ->filter()->count();

        $data['statut'] = match($occupied) {
            0 => 'libre',
            1 => $request->type === 'double' ? 'partielle' : 'occupee',
            2 => 'occupee',
        };

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

        $chambre->etudiante_1 = $request->etudiante_1;
        $chambre->etudiante_2 = $chambre->type === 'double' ? $request->etudiante_2 : null;

        $occupied = collect([$chambre->etudiante_1, $chambre->etudiante_2])
                    ->filter()->count();

        $chambre->statut = match($occupied) {
            0 => 'libre',
            1 => $chambre->type === 'double' ? 'partielle' : 'occupee',
            2 => 'occupee',
        };

        $chambre->save();

        return redirect()->route('hebergement.chambres.index')
                         ->with('success', 'Chambre mise à jour.');
    }

   public function destroy(Chambre $chambre)
{
    if (in_array($chambre->statut, ['occupee', 'partielle'])) {
        return redirect()->route('hebergement.chambres.index')
                         ->with('error', 'Impossible de supprimer une chambre occupée.');
    }

    $chambre->delete();

    return redirect()->route('hebergement.chambres.index')
                     ->with('success', 'Chambre supprimée.');
}

    public function publierVides()
    {
        $ids = Chambre::libres()->pluck('id')
                ->merge(Chambre::partielles()->pluck('id'));
        $nb = Chambre::whereIn('id', $ids)->update(['publiee' => true]);

        return redirect()->route('hebergement.chambres.index')
                         ->with('success', "$nb chambre(s) publiée(s) avec succès.");
    }
    public function depublier(Chambre $chambre)
{
    $chambre->update(['publiee' => false]);

    return back()->with('success', 'Chambre dépubliée.');
}

    public function chambresVides()
    {
        $ids = Chambre::libres()->pluck('id')
                ->merge(Chambre::partielles()->pluck('id'));
        $chambres = Chambre::where('publiee', true)
                           ->whereIn('id', $ids)
                           ->get();

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