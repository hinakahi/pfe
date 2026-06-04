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

    public function toDatabase(object $notifiable): array
    {
        return [
            'titre'   => $this->annonce->titre,
            'message' => substr($this->annonce->contenu, 0, 150) . '...',
            'type'    => 'annonce',
        ];
    }
}