<?php

namespace App\Http\Controllers\Etudiante;

use App\Http\Controllers\Controller;
use App\Models\ArticleFoyer;
use App\Models\Reservation;
use Illuminate\Http\Request;

class FoyerController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = auth()->user();
        
        $query = Reservation::where('etudiante_id', $user->id);
        
        if ($request->has('search') && $request->search) {
            $query->whereHas('article', function($q) {
                $q->where('nom_article', 'like', '%' . request('search') . '%');
            });
        }
        
        if ($request->has('statut') && $request->statut !== 'tous') {
            $query->where('statut', $request->statut);
        }
        
        $reservations = $query->latest()->get();
        
        $totalReservations = Reservation::where('etudiante_id', $user->id)
         ->where('statut', 'en_attente')
         ->count();
        $totalArticles = ArticleFoyer::where('disponible', true)->count();
        $totalPromotions = ArticleFoyer::where('promo_active', true)
        ->where('stock', '>', 0)
        ->count();
        $promotions = ArticleFoyer::where('promo_active', true)
        ->where('stock', '>', 0)
        ->latest()->limit(5)->get();
        
        return view('etudiante.foyer.dashboard', compact(
            'reservations', 'promotions',
            'totalReservations', 'totalArticles', 'totalPromotions'
        ));
    }

    public function categories()
    {
        $articles = ArticleFoyer::where('disponible', true)->latest()->get();
        return view('etudiante.foyer.articles', compact('articles'));
    }

   public function index($categorie)
{
    $query = ArticleFoyer::where('disponible', true);
    
    // Filtrer par catégorie si ce n'est pas "tous"
    if ($categorie !== 'tous') {
        $query->where('categorie', $categorie);
    }
    
    $articles = $query->latest()->paginate(12);
    
    return view('etudiante.foyer.catalogue', compact('articles', 'categorie'));
}

    public function reservations()
    {
        $reservations = Reservation::where('etudiante_id', auth()->user()->id)
            ->with('article')
            ->get();
        $promotions = [];
        return view('etudiante.foyer.reservations', compact('reservations', 'promotions'));
    }

    public function promotions()
    {
        $promotions = ArticleFoyer::where('promo_active', true)
        ->where('stock', '>', 0)
        ->paginate(12);
        return view('etudiante.foyer.promotions', compact('promotions'));
    }

    public function reserver(Request $request, ArticleFoyer $article)
    {
        $validated = $request->validate([
            'quantite' => 'required|integer|min:1',
        ]);
        
        if ($article->stock < $validated['quantite']) {
            return back()->with('error', 'Stock insuffisant');
        }
        
        Reservation::create([
            'etudiante_id' => auth()->id(),
            'article_id' => $article->id,
            'quantite' => $validated['quantite'],
            'statut' => 'panier',
        ]);
        
        return back()->with('success', 'Article ajouté au panier');
    }

    public function confirmer(Request $request)
    {
        $user = auth()->user();
        
        $panier = Reservation::where('etudiante_id', $user->id)
            ->where('statut', 'panier')
            ->get();
        
        if ($panier->isEmpty()) {
            return back()->with('error', 'Votre panier est vide');
        }
        
        foreach ($panier as $item) {
            $item->update(['statut' => 'en_attente']);
        }
        
        return back()->with('success', 'Commande confirmée. En attente de validation.');
    }

    public function annuler(Reservation $reservation)
    {
        if ($reservation->etudiante_id !== auth()->id()) {
            return back()->with('error', 'Accès non autorisé');
        }
        
        if (!in_array($reservation->statut, ['en_attente', 'panier'])) {
            return back()->with('error', 'Cette réservation ne peut pas être annulée');
        }
        
        $reservation->delete();
        return back()->with('success', 'Réservation annulée');
    }
}