<?php

namespace App\Services;

use App\Events\OrderCreated;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderProduct;
use Illuminate\Support\Facades\DB;

class OrderService
{

    public function getAllOrders($userId)
    {

        return Order::where('user_id', $userId)->get();
    }

    public function getOrderById($userId, $orderId)
    {
        $order = Order::where('id', $orderId)->where('user_id', $userId)->firstOrFail();
        $order->load('products');
        return $order;
    }
    public function createOrder($userId, $data)
    {
        return DB::transaction(function () use ($userId, $data) {
            // Create the order
            $order = Order::create([
                'user_id' => $userId,
                'status' => 'pending',
                'order_total' => 0,
                'city' => $data['city'],
                'phone' => '1212121212',
                'shipping_address' => $data['shipping_address'],
            ]);

            $orderTotal = 0;

            // Get product IDs from the request data
            $productIds = collect($data['products'])->pluck('product_id');

            // Fetch all products in the order from the database
            $products = Product::whereIn('id', $productIds)->get();

            // Attach products to the order and calculate the total
            foreach ($data['products'] as $productData) {
                $product = $products->firstWhere('id', $productData['product_id']);

                if ($product) {
                    OrderProduct::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $productData['quantity'],
                        'price' => $product->price,
                        'store_id' => $product->store_id,
                    ]);

                    $orderTotal += $product->price * $productData['quantity'];
                }
            }

            // Update the order total and refresh the order instance
            $order->update(['order_total' => $orderTotal]);
            $order->refresh();

            // Fire the OrderCreated event
            event(new OrderCreated($order));
            return $order;

        });
    }


    public function cancelOrder(Order $order)
    {
        if($order->status != 'pending' || $order->payment_status !='pending') {
            throw new \Exception('Order cannot be cancelled');
        }

        return DB::transaction(function () use ($order) {
            foreach ($order->products as $orderProduct) {
                $product = Product::find($orderProduct->pivot->product_id);
                if ($product) {
                    $product->quantity += $orderProduct->pivot->quantity;
                    $product->save();
                }
            }

            $order->status = 'cancelled';
            $order->save();

            return $order;
        });
    }


    public function deductProductQuantities(Order $order)
    {
        // Deduct quantities for each product in the order
        foreach ($order->products as $orderProduct) {
            $product = Product::find($orderProduct->pivot->product_id);
            if ($product) {
                $product->quantity -= $orderProduct->pivot->quantity;
                if ($product->quantity < 2) {
                    $product->status = 'out_of_stock';
                }
                $product->save();
            }
        }
    }
}
