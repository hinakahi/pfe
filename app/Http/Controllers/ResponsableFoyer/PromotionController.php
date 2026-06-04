<?php

namespace App\Http\Controllers\ResponsableFoyer;

use App\Http\Controllers\Controller;
use App\Models\Annonce;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Annonce::where('categorie', 'promotion')
                    ->latest()->get();
        return view('foyer.promotions.index', compact('promotions'));
    }

    public function create()
    {
        return view('foyer.promotions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre'   => 'required|string|max:191',
            'contenu' => 'required|string',
        ]);

        Annonce::create([
            'user_id'      => auth()->id(),
            'titre'        => $request->titre,
            'contenu'      => $request->contenu,
            'categorie'    => 'promotion',
            'destinataire' => 'etudiantes',
        ]);

        return redirect()->route('foyer.promotions.index')
                         ->with('success', 'Promotion publiée avec succès.');
    }

    public function edit(Annonce $promotion)
    {
        return view('foyer.promotions.edit', compact('promotion'));
    }

    public function update(Request $request, Annonce $promotion)
    {
        $request->validate([
            'titre'   => 'required|string|max:191',
            'contenu' => 'required|string',
        ]);

        $promotion->update($request->only('titre', 'contenu'));

        return redirect()->route('foyer.promotions.index')
                         ->with('success', 'Promotion modifiée avec succès.');
    }

    public function destroy(Annonce $promotion)
    {
        $promotion->delete();
        return redirect()->route('foyer.promotions.index')
                         ->with('success', 'Promotion supprimée avec succès.');
    }
}