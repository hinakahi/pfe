<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NouvelleCommande extends Notification
{
    use Queueable;

    protected $etudiante;

    public function __construct(User $etudiante)
    {
        $this->etudiante = $etudiante;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'message'         => 'Nouvelle commande de ' . $this->etudiante->name,
            'etudiante_id'    => $this->etudiante->id,
            'etudiante_name'  => $this->etudiante->name,
            'etudiante_matricule' => $this->etudiante->matricule,
            'type'            => 'nouvelle_commande',
            'url'             => '/foyer/reservations',
        ];
    }
}