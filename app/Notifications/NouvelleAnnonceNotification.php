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

    public function toArray($notifiable): array
    {
        $url = match($notifiable->role) {
            'etudiante'        => route('etudiante.annonces'),
            'technicien'       => route('technicien.annonces.index'),
            'resp_foyer'       => route('foyer.annonces.index'),
            'resp_hebergement' => route('hebergement.annonces.index'),
            'admin'            => route('admin.annonces.index'),
            default            => url('/'),
        };

        return [
            'title'      => 'Nouvelle annonce : ' . $this->annonce->titre,
            'message'    => $this->annonce->contenu,
            'categorie'  => $this->annonce->categorie,
            'annonce_id' => $this->annonce->id,
            'url'        => $url,
        ];
    }
}