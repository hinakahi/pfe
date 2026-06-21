<?php
namespace App\Notifications;
use App\Models\ContactMessage;
use Illuminate\Notifications\Notification;

class NouveauMessageAdmin extends Notification
{
    public function __construct(public ContactMessage $message) {}

    public function via($notifiable): array { return ['database']; }

    public function toDatabase($notifiable): array
    {
        return [
            'title'   => 'Nouveau message de contact',
            'message' => $this->message->nom . ' : ' . $this->message->sujet,
            'url'     => '/admin/messages/' . $this->message->id,
        ];
    }
}