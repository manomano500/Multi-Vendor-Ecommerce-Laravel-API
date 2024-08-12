<?php

namespace App\Notifications;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable ;
    protected $order;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }


    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $user = $this->order->user()->first();
        return (new MailMessage)
            ->subject(`New Order Created #`. $this->order->id)
            ->greeting('Hi '.$notifiable->name)
                    ->line("A New Order (#{$this->order->id}) Created by ".$user->name ."to Address: ".$this->order->shipping_address)
            ->action('See Order', url('http://localhost:8080/vendor'))
            //TODO
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }


    public function toDatabase(object $notifiable)
    {
        return[
            'body' => 'New Order Was Created #'. $this->order->id,
            'url' => '/orders/'.$this->order->id,
            'created_at' => $this->order->created_at->diffForHumans(), // Example of human-readable format

        ];

    }



}
