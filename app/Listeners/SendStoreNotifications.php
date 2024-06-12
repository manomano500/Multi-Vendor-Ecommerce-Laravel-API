<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Models\Product;
use App\Models\Store;
use App\Notifications\OrderPlacedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendStoreNotifications
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }



    /**
     * Handle the event.
     *
     * @param  OrderCreated  $event
     * @return void
     */
    public function handle(OrderCreated $event)
    {
        $order = $event->order;
        $orderProducts = $order->products;

        $storeProductMap = [];
        foreach ($orderProducts as $orderProduct) {
            $product = Product::find($orderProduct->product_id);
            $storeId = $product->store_id;
            if (!isset($storeProductMap[$storeId])) {
                $storeProductMap[$storeId] = [];
            }
            $storeProductMap[$storeId][] = $orderProduct;
        }

        foreach ($storeProductMap as $storeId => $orderProducts) {
            $store = Store::find($storeId);
            $user = $store->user; // Assuming you have a user relationship in your Store model
            $user->notify(new OrderPlacedNotification($orderProducts));
        }
    }


}
