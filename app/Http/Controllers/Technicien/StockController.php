<?php

namespace App\Http\Controllers\Technicien;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Models\Materiel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StockController extends Controller
{
    public function index()
    {
        $stocks = Stock::orderBy('designation')->get();
        return view('technicien.stock.index', compact('stocks'));
    }

    public function create()
    {
        return view('technicien.stock.create');
    }

    public function store(Request $request)
{
    $data = $request->validate([
        'designation'   => 'required|string|max:255',
        'quantite'      => 'required|integer|min:0',
        'unite'         => 'required|in:pcs,m,kg,litre,boite,flacon',
        'seuil_minimum' => 'required|integer|min:0',
        'categorie'     => 'required|in:electricite,plomberie,menuiserie,autre',
        'description'   => 'nullable|string',
        'photo'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    if ($request->hasFile('photo')) {
        $data['photo'] = $request->file('photo')->store('stock/photos', 'public');
    }

    Stock::create($data);

    return redirect()->route('technicien.stock.index')
                     ->with('success', 'Matériel ajouté avec succès.');
}

    public function edit(Stock $stock)
    {
        return view('technicien.stock.edit', compact('stock'));
    }

    public function update(Request $request, Stock $stock)
{
    $data = $request->validate([
        'designation'   => 'required|string|max:255',
        'quantite'      => 'required|integer|min:0',
        'unite'         => 'required|in:pcs,m,kg,litre,boite,flacon',
        'seuil_minimum' => 'required|integer|min:0',
        'categorie'     => 'required|in:electricite,plomberie,menuiserie,autre',
        'description'   => 'nullable|string',
        'photo'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    if ($request->hasFile('photo')) {
        // Supprimer l'ancienne photo si elle existe
        if ($stock->photo) {
            Storage::disk('public')->delete($stock->photo);
        }
        $data['photo'] = $request->file('photo')->store('stock/photos', 'public');
    }

    $stock->update($data);

    return redirect()->route('technicien.stock.index')
                     ->with('success', 'Matériel modifié avec succès.');
}

    public function destroy(Stock $stock)
    {
        $stock->delete();
        return redirect()->route('technicien.stock.index')
                         ->with('success', 'Matériel supprimé du stock.');
    }

    public function historique(Stock $stock)
{
    $utilisations = $stock->materiels()
        ->with('maintenance.technicien', 'maintenance.chambre', 'maintenance.etudiante')
        ->latest()
        ->paginate(10);

    return view('technicien.stock.historique', compact('stock', 'utilisations'));
}

    public function historiqueGlobal(Request $request)
    {
        $query = Materiel::with('stock', 'maintenance.technicien', 'maintenance.chambre');

        if ($request->filled('stock_id')) {
            $query->where('stock_id', $request->stock_id);
        }

        if ($request->filled('semestre')) {
            $now = now();
            // Année de début de l'année scolaire en cours (ex: si on est en mars 2026, l'année scolaire a commencé en sept 2025)
            $anneeDebut = $now->month >= 9 ? $now->year : $now->year - 1;

            if ($request->semestre === 's1') {
                $debut = \Carbon\Carbon::create($anneeDebut, 9, 1)->startOfDay();
                $fin   = \Carbon\Carbon::create($anneeDebut + 1, 1, 31)->endOfDay();
            } else { // s2
                $debut = \Carbon\Carbon::create($anneeDebut + 1, 2, 1)->startOfDay();
                $fin   = \Carbon\Carbon::create($anneeDebut + 1, 6, 30)->endOfDay();
            }

            $query->whereBetween('created_at', [$debut, $fin]);
        }

        $utilisations = $query->latest()->paginate(15);
        $stocks = Stock::orderBy('designation')->get();

        return view('technicien.stock.historique-global', compact('utilisations', 'stocks'));
    }
}