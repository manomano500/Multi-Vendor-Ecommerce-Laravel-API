<?php

namespace App\Notifications;

use App\Models\Order;
use App\Models\StoreOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewStoreOrder extends Notification
{
    use Queueable;

    protected $storeOrder;

    public function __construct(StoreOrder $storeOrder)
    {
        $this->storeOrder = $storeOrder;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('You have a new order.')
            ->action('View Order', url('/store-orders/'.$this->storeOrder->id))
            ->line('Thank you for using our application!');
    }
}
