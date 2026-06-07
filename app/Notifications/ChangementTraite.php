<?php
namespace App\Notifications;

use Illuminate\Notifications\Notification;

class ChangementTraite extends Notification
{
    protected $statut;
    protected $motif;

    public function __construct($statut, $motif = null)
    {
        $this->statut = $statut;
        $this->motif  = $motif;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => $this->statut === 'acceptee'
                ? 'Votre demande de changement de chambre a été acceptée.'
                : 'Votre demande de changement de chambre a été refusée. Motif : ' . $this->motif,
            'statut'  => $this->statut,
        ];
    }
}