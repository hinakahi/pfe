<?php
namespace App\Notifications;

use App\Models\User;
use Illuminate\Notifications\Notification;

class NouvelleReservationNotification extends Notification
{
    public function __construct(public User $etudiante) {}

    public function via($notifiable): array { return ['database']; }

    public function toArray($notifiable): array
    {
        return [
            'title'   => 'Nouvelle commande foyer',
            'message' => $this->etudiante->name . ' a passé une nouvelle commande',
            'type'    => 'nouvelle_commande',
            'url'     => '/foyer/reservations',
        ];
    }
}