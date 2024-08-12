<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewUserRegisteredNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $user;

    public function __construct($user)
    {
        $this->user = $user;

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

    public function toDatabase(object $notifiable): array
    {
        return [
            'body' => 'A new user'.$notifiable->name. 'has registered',
            'url' => '/users/'.$this->user->id,
            'created_at' => $this->user->created_at->diffForHumans(), // Example of human-readable format


        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'A new user has registered.',
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
        ];
    }
}
