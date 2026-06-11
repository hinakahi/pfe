<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use Carbon\Carbon;

class AnnulerReservationsExpirees extends Command
{
    protected $signature   = 'reservations:annuler-expirees';
    protected $description = 'Annule les réservations expirées (en_attente +3h / validee non récupérée +4h)';

    public function handle()
    {
        // 1️⃣ Annuler les "en_attente" après 3h (ancien comportement)
        $count1 = Reservation::where('statut', 'en_attente')
            ->where('created_at', '<=', Carbon::now()->subHours(3))
            ->update(['statut' => 'annulee']);

        // 2️⃣ Annuler les "validee" non récupérées après 4h
        $expirees = Reservation::where('statut', 'validee')
            ->where('validee_at', '<=', Carbon::now()->subMinutes(3))
            ->get();

        foreach ($expirees as $reservation) {
            // Remettre le stock
            $reservation->article->increment('stock', $reservation->quantite);

            $reservation->update([
                'statut'     => 'annulee',
                'validee_at' => null,
            ]);
        }

        $this->info("$count1 réservation(s) en_attente annulée(s).");
        $this->info("{$expirees->count()} réservation(s) validée(s) non récupérées annulée(s).");
    }
}