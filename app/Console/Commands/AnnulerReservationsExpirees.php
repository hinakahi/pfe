<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use Carbon\Carbon;

class AnnulerReservationsExpirees extends Command
{
    protected $signature   = 'reservations:annuler-expirees';
    protected $description = 'Annule automatiquement les réservations en attente depuis plus de 3h';

    public function handle()
    {
        $expiration = Carbon::now()->subHours(3);

        $count = Reservation::where('statut', 'en_attente')
            ->where('created_at', '<=', $expiration)
            ->update([
                'statut' => 'annulee',
            ]);

        $this->info("$count réservation(s) annulée(s) automatiquement.");
    }
}