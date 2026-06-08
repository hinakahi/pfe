<?php

namespace App\Http\Controllers\Etudiante;

use App\Http\Controllers\Controller;
use App\Models\ArticleFoyer;
use App\Models\Reservation;
use App\Models\Annonce;
use App\Models\User;
use Illuminate\Http\Request;

class FoyerController extends Controller
{
    // ─── Dashboard — 3 cartes ────────────────────────────────
    public function dashboard()
    {
        $reservations      = Reservation::where('etudiante_id', auth()->id())->get();
        $totalReservations = $reservations->count();
        $enAttente         = $reservations->where('statut', 'en_attente')->count();
        $validees          = $reservations->where('statut', 'validee')->count();
        $annulees          = $reservations->where('statut', 'annulee')->count();
        $totalArticles     = ArticleFoyer::where('disponible', true)->where('stock', '>', 0)->count();
        $promotions        = Annonce::where('categorie', 'promotion')
                             ->where('destinataire', 'etudiantes')
                             ->latest()->get();
        $totalPromotions   = $promotions->count();

        return view('etudiante.foyer.dashboard', compact(
            'totalReservations', 'enAttente', 'validees', 'annulees',
            'totalArticles', 'promotions', 'totalPromotions'
        ));
    }

    // ─── Page 3 cartes catégories ────────────────────────────
    public function categories()
    {
        $fastfood  = ArticleFoyer::where('categorie', 'fastfood')
                     ->where('disponible', true)->where('stock', '>', 0)->count();
        $cafeteria = ArticleFoyer::where('categorie', 'cafeteria')
                     ->where('disponible', true)->where('stock', '>', 0)->count();
        $magasin   = ArticleFoyer::where('categorie', 'magasin')
                     ->where('disponible', true)->where('stock', '>', 0)->count();

        $panierCount = Reservation::where('etudiante_id', auth()->id())
                       ->where('statut', 'panier')->count();

        return view('etudiante.foyer.categories', compact(
            'fastfood', 'cafeteria', 'magasin', 'panierCount'
        ));
    }

    // ─── Liste articles par catégorie ────────────────────────
    public function index(Request $request, $categorie)
    {
        $query = ArticleFoyer::where('categorie', $categorie)
                 ->where('disponible', true)
                 ->where('stock', '>', 0);

        // Recherche
        if ($request->search) {
            $query->where('nom_article', 'like', '%' . $request->search . '%');
        }

        $articles = $query->latest()->get();

        $panier = Reservation::where('etudiante_id', auth()->id())
                  ->where('statut', 'panier')
                  ->with('article')->get();

        return view('etudiante.foyer.index', compact('articles', 'categorie', 'panier'));
    }

    // ─── Mes réservations ────────────────────────────────────
    public function mesReservations(Request $request)
    {
        $query = Reservation::where('etudiante_id', auth()->id())
                 ->with('article');

        // Filtre statut
        if ($request->statut && $request->statut !== 'tous') {
            $query->where('statut', $request->statut);
        }

        // Recherche par nom article
        if ($request->search) {
            $query->whereHas('article', function ($q) use ($request) {
                $q->where('nom_article', 'like', '%' . $request->search . '%');
            });
        }

        $reservations = $query->latest()->get();

        $promotions = Annonce::where('categorie', 'promotion')
                     ->where('destinataire', 'etudiantes')
                     ->latest()->take(3)->get();

        return view('etudiante.foyer.reservations', compact('reservations', 'promotions'));
    }

    // ─── Promotions ──────────────────────────────────────────
    public function promotions()
    {
        $promotions = Annonce::where('categorie', 'promotion')
                     ->where('destinataire', 'etudiantes')
                     ->latest()->get();

        return view('etudiante.foyer.promotions', compact('promotions'));
    }

    // ─── Ajouter au panier ───────────────────────────────────
    public function reserver(Request $request, ArticleFoyer $article)
    {
        $request->validate([
            'quantite' => 'required|integer|min:1|max:' . $article->stock,
        ]);

        if (!$article->isEnStock()) {
            return back()->with('error', 'Article non disponible.');
        }

        // Si déjà dans le panier → incrémenter
        $existing = Reservation::where('etudiante_id', auth()->id())
                    ->where('article_id', $article->id)
                    ->where('statut', 'panier')
                    ->first();

        if ($existing) {
            $existing->increment('quantite', $request->quantite);
        } else {
            Reservation::create([
                'etudiante_id' => auth()->id(),
                'article_id'   => $article->id,
                'quantite'     => $request->quantite,
                'statut'       => 'panier',
            ]);
        }

        return back()->with('success', 'Article ajouté au panier !');
    }

    // ─── Commander (panier → en_attente + notification) ──────
    public function confirmer()
    {
        $reservations = Reservation::where('etudiante_id', auth()->id())
                        ->where('statut', 'panier')->get();

        if ($reservations->isEmpty()) {
            return back()->with('error', 'Votre panier est vide.');
        }

        foreach ($reservations as $r) {
            $r->update(['statut' => 'en_attente']);
        }

        // Notification au responsable foyer
        $respFoyer = User::where('role', 'resp_foyer')->first();
        if ($respFoyer) {
            $respFoyer->notify(new \App\Notifications\NouvelleCommande(auth()->user()));
        }

        return redirect()->route('etudiante.foyer.reservations')
                         ->with('success', 'Commande envoyée avec succès !');
    }

    // ─── Retirer du panier ───────────────────────────────────
    public function annuler(Reservation $reservation)
    {
        if ($reservation->etudiante_id !== auth()->id()) {
            abort(403);
        }

        if (!in_array($reservation->statut, ['en_attente', 'panier'])) {
            return back()->with('error', 'Impossible d\'annuler cette réservation.');
        }

        $reservation->delete();
        return back()->with('success', 'Article retiré.');
    }
}