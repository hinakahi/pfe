<?php

namespace App\Http\Controllers\Technicien;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use Illuminate\Http\Request;

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
        $request->validate([
            'designation'   => 'required|string|max:191',
            'quantite'      => 'required|integer|min:0',
            'unite'         => 'required|string|max:50',
            'seuil_minimum' => 'required|integer|min:0',
            'categorie'     => 'required|in:electricite,plomberie,menuiserie,climatisation,autre',
            'description'   => 'nullable|string|max:500',
        ]);

        Stock::create($request->all());

        return redirect()->route('technicien.stock.index')
                         ->with('success', 'Matériel ajouté au stock avec succès.');
    }

    public function edit(Stock $stock)
    {
        return view('technicien.stock.edit', compact('stock'));
    }

    public function update(Request $request, Stock $stock)
    {
        $request->validate([
            'designation'   => 'required|string|max:191',
            'quantite'      => 'required|integer|min:0',
            'unite'         => 'required|string|max:50',
            'seuil_minimum' => 'required|integer|min:0',
            'categorie'     => 'required|in:electricite,plomberie,menuiserie,climatisation,autre',
            'description'   => 'nullable|string|max:500',
        ]);

        $stock->update($request->all());

        return redirect()->route('technicien.stock.index')
                         ->with('success', 'Stock mis à jour avec succès.');
    }

    public function destroy(Stock $stock)
    {
        $stock->delete();
        return redirect()->route('technicien.stock.index')
                         ->with('success', 'Matériel supprimé du stock.');
    }
}