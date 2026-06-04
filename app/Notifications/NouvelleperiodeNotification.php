<?php
namespace App\Notifications;

use App\Models\Periode;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class NouvelleperiodeNotification extends Notification
{
    public function __construct(public Periode $periode) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'titre'   => 'Nouvelle période ouverte',
            'message' => "La période de {$this->periode->type} \"{$this->periode->libelle}\" est ouverte du {$this->periode->date_debut->format('d/m/Y')} au {$this->periode->date_fin->format('d/m/Y')}.",
            'type'    => 'periode',
        ];
    }
}