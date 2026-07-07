<?php

namespace App\Http\Controllers\Technicien;

use App\Http\Controllers\Controller;
use App\Models\Materiel;
use App\Models\Stock;
use Illuminate\Http\Request;

class IncidentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'maintenance_id'       => 'required|exists:maintenances,id',
            'stock_id'             => 'required|exists:stocks,id',
            
            'quantite'             => 'required|integer|min:1',
            'description_incident' => 'required|string|max:500',
        ]);
        $stock = Stock::findOrFail($request->stock_id);

        $quantiteRetiree = min($request->quantite, $stock->quantite);
        $nouvelleQuantite = max(0, $stock->quantite - $quantiteRetiree);

        $stock->update([
            'quantite'   => $nouvelleQuantite,
            
        ]);

        Materiel::create([
            'maintenance_id'       => $request->maintenance_id,
            'stock_id'             => $request->stock_id,
            'quantite'             => $request->quantite,
            'stock_epuise'         => $nouvelleQuantite === 0,
            'description_incident' => $request->description_incident,
        ]);

        return redirect()->back()
                         ->with('success', 'Incident signalé avec succès.');
    }
}