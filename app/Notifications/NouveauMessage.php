<?php

namespace App\Notifications;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NouveauMessage extends Notification
{
    use Queueable;

    protected $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'message'             => 'Nouveau message de ' . $this->message->sender->name,
            'etudiante_id'        => $this->message->sender->id,
            'etudiante_name'      => $this->message->sender->name,
            'etudiante_matricule' => $this->message->sender->matricule,
            'message_id'          => $this->message->id,
            'apercu'              => \Str::limit($this->message->body, 80),
            'type'                => 'nouveau_message',
            'url'                 => '/admin/messages/' . $this->message->id,
        ];
    }
}