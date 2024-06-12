<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderPlacedNotification extends Notification
{
    use Queueable;

    protected $orderProducts;

    public function __construct($orderProducts)
    {
        $this->orderProducts = $orderProducts;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toDatabase($notifiable)
    {
        return new DatabaseMessage([
            'message' => 'You have new products ordered from your store.',
            'order_products' => $this->orderProducts,
            'url' => url('/orders')
        ]);
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('You have new products ordered from your store.')
            ->action('View Orders', url('/orders'))
            ->line('Thank you for using our application!');
    }

    public function toArray($notifiable)
    {
        return [
            'orderProducts' => $this->orderProducts
        ];
    }
}
