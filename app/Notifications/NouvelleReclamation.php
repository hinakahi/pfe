<?php

namespace App\Notifications;

use App\Models\Reclamation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NouvelleReclamation extends Notification
{
    use Queueable;

    protected $reclamation;

    public function __construct(Reclamation $reclamation)
    {
        $this->reclamation = $reclamation;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'message'             => 'Nouvelle réclamation de ' . $this->reclamation->etudiante->name,
            'etudiante_id'        => $this->reclamation->etudiante->id,
            'etudiante_name'      => $this->reclamation->etudiante->name,
            'etudiante_matricule' => $this->reclamation->etudiante->matricule,
            'reclamation_id'      => $this->reclamation->id,
            'reclamation_sujet'   => $this->reclamation->sujet,
            'type'                => 'nouvelle_reclamation',
            'url'                 => '/admin/reclamations/' . $this->reclamation->id,
        ];
    }
}