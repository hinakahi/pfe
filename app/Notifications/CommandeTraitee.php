<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class CommandeTraitee extends Notification
{
    protected $commande;
    protected $statut;
    protected $motif;

    public function __construct($commande, string $statut, string $motif = null)
    {
        $this->commande = $commande;
        $this->statut   = $statut;
        $this->motif    = $motif;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'commande_id' => $this->commande->id,
            'article'     => $this->commande->articleFoyer->nom ?? 'Article',
            'quantite'    => $this->commande->quantite,
            'statut'      => $this->statut,
            'message'     => $this->statut === 'acceptee'
                ? "Votre commande a été acceptée."
                : "Votre commande a été refusée." . ($this->motif ? " Motif : {$this->motif}" : ''),
        ];
    }
}