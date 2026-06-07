<?php

namespace App\Http\Controllers\Etudiante;

use App\Http\Controllers\Controller;
use App\Models\ArticleFoyer;
use App\Models\Reservation;
use Illuminate\Http\Request;

class FoyerController extends Controller
{
    public function dashboard()
{
    $reservations = Reservation::where('etudiante_id', auth()->id())->get();
    
    $totalReservations = $reservations->count();
    $enAttente = $reservations->where('statut', 'en_attente')->count();
    $validees = $reservations->where('statut', 'validee')->count();
    $annulees = $reservations->where('statut', 'annulee')->count();
    
    $totalArticles = ArticleFoyer::where('disponible', true)->where('stock', '>', 0)->count();
    
    $promotions = \App\Models\Annonce::where('categorie', 'promotion')
        ->where('destinataire', 'etudiantes')
        ->latest()
        ->get();
    
    $totalPromotions = $promotions->count();
    
    return view('etudiante.foyer.dashboard', compact(
        'totalReservations', 'enAttente', 'validees', 'annulees',
        'totalArticles', 'promotions', 'totalPromotions'
    ));
}

 public function index()
{
    $articles = ArticleFoyer::where('disponible', true)
        ->where('stock', '>', 0)
        ->latest()->get();
    
    // 🎁 Récupérer les promotions depuis Annonce
    $promotions = \App\Models\Annonce::where('categorie', 'promotion')
        ->where('destinataire', 'etudiantes')
        ->latest()
        ->get();
    
    $categories = ArticleFoyer::distinct()->pluck('categorie');
    
    return view('etudiante.foyer.index', compact('articles', 'promotions', 'categories'));
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
        'etudiante_id' => auth()->id(),
        'article_id'   => $article->id,
        'quantite'     => $request->quantite,
        'statut'       => 'panier',  // ← panier au lieu de en_attente
    ]);

    return back()->with('success', 'Article ajouté au panier !');
}
public function confirmer()
{
    $reservations = Reservation::where('etudiante_id', auth()->id())
                   ->where('statut', 'panier')
                   ->get();

    if ($reservations->isEmpty()) {
        return back()->with('error', 'Votre panier est vide.');
    }

    foreach ($reservations as $r) {
        $r->update(['statut' => 'en_attente']);
    }

    return redirect()->route('etudiante.foyer.reservations')
                     ->with('success', 'Réservation confirmée avec succès !');
}
public function mesReservations()
{
    $reservations = Reservation::where('etudiante_id', auth()->id())
                   ->with('article')
                   ->latest()
                   ->get();

    $promotions = \App\Models\Annonce::where('categorie', 'promotion')
                 ->where('publiee', true)
                 ->latest()
                 ->take(3)
                 ->get();

    return view('etudiante.foyer.reservations', compact('reservations', 'promotions'));
}

    public function annuler(Reservation $reservation)
{
    if ($reservation->etudiante_id !== auth()->id()) {
        abort(403);
    }

    if (!in_array($reservation->statut, ['en_attente', 'panier'])) {
        return back()->with('error', 'Impossible d\'annuler cette réservation.');
    }

    $reservation->delete(); // ← supprime du panier au lieu de changer le statut
    return back()->with('success', 'Article retiré du panier.');
}

}