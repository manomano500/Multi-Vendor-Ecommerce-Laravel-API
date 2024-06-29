<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Models\User;
use App\Notifications\OrderCreatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendOrderCreatedNotification implements ShouldQueue


{
    use InteractsWithQueue;
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderCreated $event): void
    {
        // Get unique stores involved in the order
        $uniqueStores = $event->order->products->map->store->unique('id');

        foreach ($uniqueStores as $store) {
            $storeOwner = $store->user;
            $storeOwner->notify(new OrderCreatedNotification($event->order));

        }
        //notify the admins
        User::where('role_id', '1')->get()->each->notify(new OrderCreatedNotification($event->order));


        //
    }
}
