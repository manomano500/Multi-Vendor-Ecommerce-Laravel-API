<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
class OrderStatusUpdated extends Notification
{
    use Queueable;

    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['mail','database']; // You can add more channels here
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('The status of your order has been updated.')
            ->line('New status: ' . $this->order->status)
            ->action('View Order', url('/orders/' . $this->order->id))
            ->line('Thank you for your business!');
    }

    public function toDatabase($notifiable){
        return [
            'data' => 'Your order status has been updated to ' . $this->order->status,
            'url' => '/orders/' . $this->order->id
        ];





    }
}
