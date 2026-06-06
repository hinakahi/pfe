<?php
namespace App\Notifications;

use App\Models\Annonce;
use Illuminate\Notifications\Notification;

class NouvelleAnnonceNotification extends Notification
{
    public function __construct(public Annonce $annonce) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

  public function toDatabase($notifiable): array
{
    return [
        'title'    => $this->annonce->titre,      // ← 'title' pas 'titre'
        'message'  => $this->annonce->contenu,    // ← 'message' pas 'contenu'
        'categorie'  => $this->annonce->categorie,
        'annonce_id' => $this->annonce->id,
    ];
}
}