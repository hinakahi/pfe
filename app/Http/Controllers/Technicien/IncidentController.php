<?php

namespace App\Http\Controllers\Technicien;

use App\Http\Controllers\Controller;
use App\Models\Materiel;
use Illuminate\Http\Request;

class IncidentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'maintenance_id'       => 'required|exists:maintenances,id',
            'nom_materiel'         => 'required|string|max:191',
            'quantite'             => 'required|integer|min:1',
            'description_incident' => 'required|string|max:500',
        ]);

        Materiel::create([
            'maintenance_id'       => $request->maintenance_id,
            'nom_materiel'         => $request->nom_materiel,
            'quantite'             => $request->quantite,
            'stock_epuise'         => false,
            'description_incident' => $request->description_incident,
        ]);

        return redirect()->back()
                         ->with('success', 'Incident signalé avec succès.');
    }
}