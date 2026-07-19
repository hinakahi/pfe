<?php
namespace App\Notifications;
use App\Models\Stock;
use Illuminate\Notifications\Notification;

class StockEpuiseNotification extends Notification
{
    public function __construct(public Stock $stock) {}
    public function via($notifiable): array { return ['database']; }
    public function toDatabase($notifiable): array
    {
        return [
            'title'   => '⚠️ Stock épuisé',
            'message' => $this->stock->designation . ' est en dessous du seuil minimum (' . $this->stock->quantite . ' restant)',
            'url'     => route('technicien.stock.edit', $this->stock->id),
        ];
    }
}