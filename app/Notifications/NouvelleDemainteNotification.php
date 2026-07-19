<?php
namespace App\Notifications;

use App\Models\Maintenance;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class NouvelleDemainteNotification extends Notification
{
    public function __construct(public Maintenance $maintenance) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title'          => 'Nouvelle demande de maintenance',
            'message'        => $this->maintenance->type . ' - ' . Str::limit($this->maintenance->description, 80),
            'maintenance_id' => $this->maintenance->id,
            'url'            => route('technicien.demandes.show', $this->maintenance->id),
        ];
    }
}