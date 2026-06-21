<?php
namespace App\Notifications;
use App\Models\Reclamation;
use Illuminate\Notifications\Notification;

class NouvelleReclamationAdmin extends Notification
{
    public function __construct(public Reclamation $reclamation) {}

    public function via($notifiable): array { return ['database']; }

    public function toDatabase($notifiable): array
    {
        return [
            'title'   => 'Nouvelle réclamation',
            'message' => $this->reclamation->sujet ?? 'Une nouvelle réclamation a été soumise',
            'url'     => '/admin/reclamations/' . $this->reclamation->id,
        ];
    }
}