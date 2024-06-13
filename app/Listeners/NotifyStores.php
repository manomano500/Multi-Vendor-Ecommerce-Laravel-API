<?php

namespace App\Listeners;

use App\Models\StoreOrder;
use App\Notifications\NewStoreOrder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\OrderCreated;

class NotifyStores implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(OrderCreated $event)
    {
        $order = $event->order;
        foreach ($order->orderProducts as $orderProduct) {
            $storeOrder = StoreOrder::create([
                'order_id' => $order->id,
                'store_id' => $orderProduct->store_id,
                'status' => 'pending',
            ]);
            $storeOrder->store->user->notify(new NewStoreOrder($storeOrder));
        }
    }

}
