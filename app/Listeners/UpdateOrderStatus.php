<?php

namespace App\Listeners;

use App\Events\OrderProductUpdated;
use App\Models\Order;

class UpdateOrderStatus
{

    //unused
    public function handle(OrderProductUpdated $event)
    {
        $order = $event->orderProduct->order;
        $orderProducts = $order->orderProducts;

        // Check if all order products are 'dropped_off'
        if ($orderProducts->every(fn($item) => $item->status === 'in_stock')) {
            $order->status = 'ready_for_shipment'; // Assuming this is your intended status
            $order->save();
        }

        elseif ($order->status !== 'processing') {
            $order->status = 'processing';
            $order->save();
        }
    }
}
