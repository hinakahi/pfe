<?php
namespace App\Notifications;

use Illuminate\Notifications\Notification;

class NouvelleDemandeChambre extends Notification
{
    protected $type;
    protected $nomEtudiante;
    protected $numeroChambre;

    public function __construct($type, $nomEtudiante, $numeroChambre)
    {
        $this->type          = $type;
        $this->nomEtudiante  = $nomEtudiante;
        $this->numeroChambre = $numeroChambre;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $typeLabel = $this->type === 'renouvellement' ? 'renouvellement' : 'changement';

        return [
            'message' => "Nouvelle demande de {$typeLabel} de {$this->nomEtudiante} pour la chambre {$this->numeroChambre}.",
            'type'    => $this->type,
            'url'     => $this->type === 'renouvellement'
                ? route('hebergement.renouvellements.index')
                : route('hebergement.changements.index'),
        ];
    }
}