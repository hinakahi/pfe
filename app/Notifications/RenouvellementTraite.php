<?php
namespace App\Notifications;

use Illuminate\Notifications\Notification;

class RenouvellementTraite extends Notification
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
            'message' => $this->statut === 'validee'
                ? 'Votre demande de renouvellement a été validée.'
                : 'Votre demande de renouvellement a été refusée. Motif : ' . $this->motif,
            'statut'  => $this->statut,
            'url'     => route('etudiante.hebergement.demandes'),
        ];
    }
}