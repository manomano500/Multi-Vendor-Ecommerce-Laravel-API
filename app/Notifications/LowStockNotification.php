<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LowStockNotification extends Notification
{
    use Queueable;

    protected $product;

    public function __construct($product)
    {
        $this->product = $product;
    }

    public function via($notifiable)
    {
        return ['database']; // Use the 'database' channel
    }

    public function toArray($notifiable)
    {
        return [
            'body' => 'The product "' . $this->product->name . '" has low stock with only ' . $this->product->quantity . ' pieces left.',
            'url' => '/products/' . $this->product->id,
            'created_at' => now()->diffForHumans(), // Using current time in human-readable format
        ];
    }
}
