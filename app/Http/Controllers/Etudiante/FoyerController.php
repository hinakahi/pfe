<?php

namespace App\Http\Controllers\Etudiante;

use App\Http\Controllers\Controller;
use App\Models\ArticleFoyer;
use App\Models\Reservation;
use Illuminate\Http\Request;

class FoyerController extends Controller
{
    public function index()
    {
        $articles = ArticleFoyer::where('disponible', true)
                   ->where('stock', '>', 0)
                   ->latest()->get();
        return view('etudiante.foyer.index', compact('articles'));
    }

    public function reserver(Request $request, ArticleFoyer $article)
    {
        $request->validate([
            'quantite' => 'required|integer|min:1|max:'.$article->stock,
        ]);

        if (!$article->isEnStock()) {
            return back()->with('error', 'Article non disponible.');
        }

        Reservation::create([
            'etudiante_id'    => auth()->id(),
            'article_id'      => $article->id,
            'quantite'        => $request->quantite,
            'statut'          => 'en_attente',
        ]);

        return redirect()->route('etudiante.foyer.reservations')
                         ->with('success', 'Réservation effectuée avec succès.');
    }

    public function mesReservations()
    {
        $reservations = Reservation::where('etudiante_id', auth()->id())
                       ->with('article')
                       ->latest()
                       ->get();
        return view('etudiante.foyer.reservations', compact('reservations'));
    }

    public function annuler(Reservation $reservation)
    {
        if ($reservation->etudiante_id !== auth()->id()) {
            abort(403);
        }

        if ($reservation->statut !== 'en_attente') {
            return back()->with('error', 'Impossible d\'annuler cette réservation.');
        }

        $reservation->update(['statut' => 'annulee']);
        return redirect()->route('etudiante.foyer.reservations')
                         ->with('success', 'Réservation annulée avec succès.');
    }
}