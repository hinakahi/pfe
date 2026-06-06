<?php

namespace App\Notifications;

use App\Models\Maintenance;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NouvelleDemainteNotification extends Notification
{
    use Queueable;

    public function __construct(public Maintenance $maintenance) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'message'        => 'Nouvelle demande de maintenance reçue.',
            'maintenance_id' => $this->maintenance->id,
            'description'    => $this->maintenance->description,
            'type'           => $this->maintenance->type,
            'urgence'        => $this->maintenance->urgence,
            'etudiante'      => $this->maintenance->etudiante->name ?? '-',
            'chambre'        => $this->maintenance->chambre->numero ?? '-',
        ];
    }
}