<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Models\User;
use App\Notifications\NewUserRegisteredNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendNewUserNotification implements  ShouldQueue
{
    use InteractsWithQueue;
    /**
     * Create the event listener.
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        Log::info('New User Registered'.$event->user);
        Log::info('New User Registered: ' . $event->user->id);

        // Fetching all admins
        $admins = User::where('role_id', 1)->get();
        Log::info('Number of Admins: ' . $admins->count());

        foreach ($admins as $admin) {
            $admin->notify(new NewUserRegisteredNotification($event->user));
            Log::info('Notification sent to admin: ' . $admin->id);
        }
    }
}
