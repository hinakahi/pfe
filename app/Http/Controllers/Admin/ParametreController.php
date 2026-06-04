<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Periode;
use Illuminate\Http\Request;

class ParametreController extends Controller
{
    public function index()
    {
        $periodes = Periode::latest()->get();
        return view('admin.parametres.index', compact('periodes'));
    }

    public function create()
    {
        return view('admin.parametres.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type'        => 'required|in:renouvellement,changement',
            'date_debut'  => 'required|date',
            'date_fin'    => 'required|date|after:date_debut',
            'description' => 'nullable|string',
        ]);

        Periode::create([
            'admin_id'    => auth()->id(),
            'type'        => $request->type,
            'date_debut'  => $request->date_debut,
            'date_fin'    => $request->date_fin,
            'description' => $request->description,
            'active'      => true,
        ]);

        return redirect()->route('admin.parametres.index')
                         ->with('success', 'Période créée avec succès.');
    }

    public function edit(Periode $parametre)
    {
        return view('admin.parametres.edit', compact('parametre'));
    }

    public function update(Request $request, Periode $parametre)
    {
        $request->validate([
            'type'        => 'required|in:renouvellement,changement',
            'date_debut'  => 'required|date',
            'date_fin'    => 'required|date|after:date_debut',
            'description' => 'nullable|string',
            'active'      => 'boolean',
        ]);

        $parametre->update($request->only('type', 'date_debut', 'date_fin', 'description', 'active'));

        return redirect()->route('admin.parametres.index')
                         ->with('success', 'Période modifiée avec succès.');
    }

    public function destroy(Periode $parametre)
    {
        $parametre->delete();
        return redirect()->route('admin.parametres.index')
                         ->with('success', 'Période supprimée avec succès.');
    }
}