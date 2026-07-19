<?php

namespace App\Notifications;

use App\Models\Maintenance;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class MaintenanceTermineeNotification extends Notification
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
            'message'        => 'Votre demande de maintenance a été traitée et clôturée.',
            'maintenance_id' => $this->maintenance->id,
            'description'    => $this->maintenance->description,
            'chambre'        => $this->maintenance->chambre->numero ?? '-',
            'url'            => route('etudiante.maintenance.show', $this->maintenance->id),
        ];
    }
}