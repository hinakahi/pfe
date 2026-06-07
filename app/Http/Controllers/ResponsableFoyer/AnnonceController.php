<?php

namespace App\Http\Controllers\ResponsableFoyer;

use App\Http\Controllers\Controller;
use App\Models\Annonce;
use App\Models\User;
use App\Notifications\NouvelleAnnonceNotification;
use Illuminate\Http\Request;

class AnnonceController extends Controller
{
    public function index()
{
    $annonces = Annonce::where('user_id', auth()->id())
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
        $validated = $request->validate([
    'titre'     => 'required|string|max:191',
    'contenu'   => 'required|string',
    'categorie' => 'required|in:generale,hebergement,foyer,maintenance,promotion',
    'urgence'   => 'required|in:general,urgent,administration',
]);
        $annonce = Annonce::create([
    'user_id'          => auth()->id(),
    'titre'            => $validated['titre'],
    'contenu'          => $validated['contenu'],
    'categorie'        => $validated['categorie'],
    'urgence'          => $validated['urgence'],
    'destinataire'     => 'etudiantes',
    'publiee'          => true,
    'date_publication' => now(),
]);

        // Notifier toutes les étudiantes
        User::where('role', 'etudiante')->each(function ($etudiante) use ($annonce) {
            $etudiante->notify(new NouvelleAnnonceNotification($annonce));
        });

        return redirect()
            ->route('foyer.annonces.index')
            ->with('success', 'Annonce publiée avec succès.');
    }

    public function edit(Annonce $annonce)
    {
        if ($annonce->user_id !== auth()->id()) abort(403);
        return view('foyer.annonces.edit', compact('annonce'));
    }

    public function update(Request $request, Annonce $annonce)
    {
        if ($annonce->user_id !== auth()->id()) abort(403);

        $validated = $request->validate([
            'titre'     => 'required|string|max:191',
            'contenu'   => 'required|string',
            'categorie' => 'required|in:generale,urgente,evenement',
        ]);

        $annonce->update($validated);

        // Notifier toutes les étudiantes de la modification
        User::where('role', 'etudiante')->each(function ($etudiante) use ($annonce) {
           $etudiante->notify(new NouvelleAnnonceNotification($annonce));
        });

        return redirect()
            ->route('foyer.annonces.index')
            ->with('success', 'Annonce modifiée avec succès.');
    }

    public function destroy(Annonce $annonce)
    {
        if ($annonce->user_id !== auth()->id()) abort(403);
        $annonce->delete();

        return redirect()
            ->route('foyer.annonces.index')
            ->with('success', 'Annonce supprimée.');
    }
}