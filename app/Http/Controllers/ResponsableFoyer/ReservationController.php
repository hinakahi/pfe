<?php

namespace App\Http\Controllers\ResponsableFoyer;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    /**
     * Afficher la liste avec filtre par statut + compteurs pour les onglets.
     */
    public function index(Request $request)
    {
        $filtre = $request->query('statut', 'tous');

        $query = Reservation::with('etudiante', 'article')->latest();

        if ($filtre !== 'tous') {
            $query->where('statut', $filtre);
        }

        $reservations = $query->paginate(15)->withQueryString();

        // Compteurs pour les badges des onglets
        $compteurs = [
            'en_attente' => Reservation::where('statut', 'en_attente')->count(),
            'validee'    => Reservation::where('statut', 'validee')->count(),
            'refusee'    => Reservation::where('statut', 'refusee')->count(),
        ];

        return view('foyer.reservations.index', compact('reservations', 'filtre', 'compteurs'));
    }

    /**
     * Valider une réservation — décrémente le stock.
     */
    public function valider(Request $request, Reservation $reservation)
    {
        if ($reservation->statut !== 'en_attente') {
            return back()->with('error', 'Cette réservation a déjà été traitée.');
        }

        if ($reservation->article->stock < $reservation->quantite) {
            return back()->with('error', 'Stock insuffisant pour valider cette réservation.');
        }

        // Décrémenter le stock
        $reservation->article->decrement('stock', $reservation->quantite);

        // Si stock tombe à 0, marquer indisponible
        if ($reservation->article->fresh()->stock === 0) {
            $reservation->article->update(['disponible' => false]);
        }

        $reservation->update([
            'statut'        => 'validee',
            'resp_foyer_id' => auth()->id(),
        ]);

        return redirect()->route('foyer.reservations')
                         ->with('success', "Réservation #{$reservation->id} validée avec succès.");
    }

    /**
     * Refuser une réservation avec motif optionnel.
     */
    public function refuser(Request $request, Reservation $reservation)
    {
        if ($reservation->statut !== 'en_attente') {
            return back()->with('error', 'Cette réservation a déjà été traitée.');
        }

        $request->validate([
            'motif_refus' => 'nullable|string|max:500',
        ]);

        $reservation->update([
            'statut'        => 'refusee',
            'resp_foyer_id' => auth()->id(),
        ]);

        return redirect()->route('foyer.reservations')
                         ->with('info', "Réservation #{$reservation->id} refusée.");
    }
}