<?php

namespace App\Http\Controllers\ResponsableFoyer;

use App\Http\Controllers\Controller;
use App\Models\Annonce;
use Illuminate\Http\Request;

class AnnonceController extends Controller
{
    public function index()
    {
        $annonces = Annonce::where('user_id', auth()->id())
                           ->orWhere('destinataire', 'etudiantes')
                           ->latest()
                           ->paginate(10);

        return view('foyer.annonces.index', compact('annonces'));
    }

    public function create()
    {
        return view('foyer.annonces.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre'   => 'required|string|max:191',
            'contenu' => 'required|string',
            'categorie' => 'required|in:generale,urgente,evenement',
        ]);

        Annonce::create([
            'user_id'          => auth()->id(),
            'titre'            => $request->titre,
            'contenu'          => $request->contenu,
            'categorie'        => $request->categorie,
            'destinataire'     => 'etudiantes',
            'date_publication' => now(),
        ]);

        return redirect()->route('foyer.annonces.index')
                         ->with('success', 'Annonce publiée avec succès.');
    }

    public function destroy(Annonce $annonce)
    {
        $annonce->delete();
        return redirect()->route('foyer.annonces.index')
                         ->with('success', 'Annonce supprimée.');
    }
    public function edit(Annonce $annonce)
{
    return view('foyer.annonces.edit', compact('annonce'));
}

public function update(Request $request, Annonce $annonce)
{
    $request->validate([
        'titre'     => 'required|string|max:191',
        'contenu'   => 'required|string',
        'categorie' => 'required|in:generale,urgente,evenement',
    ]);

    $annonce->update([
        'titre'     => $request->titre,
        'contenu'   => $request->contenu,
        'categorie' => $request->categorie,
    ]);

    return redirect()->route('foyer.annonces.index')
                     ->with('success', 'Annonce modifiée avec succès.');
}
}