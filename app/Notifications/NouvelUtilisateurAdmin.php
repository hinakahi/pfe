<?php
namespace App\Notifications;
use App\Models\User;
use Illuminate\Notifications\Notification;

class NouvelUtilisateurAdmin extends Notification
{
    public function __construct(public User $user) {}

    public function via($notifiable): array { return ['database']; }

    public function toDatabase($notifiable): array
    {
        return [
            'title'   => 'Nouvel utilisateur inscrit',
            'message' => $this->user->name . ' (' . $this->user->role . ') vient de s\'inscrire',
            'url'     => '/admin/utilisateurs',
        ];
    }
}