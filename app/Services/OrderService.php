<?php

namespace App\Services;

use App\Events\OrderCreated;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        try {

            // Start the transaction
            $order = DB::transaction(function () use ($userId, $data) {
                // Create the order
                $order = Order::create([
                    'user_id' => $userId,
                    'status' => 'pending',
                    'order_total' => 0,
                    'city' => $data['city'],
//                    'phone' => $data['phone'],
                    'shipping_address' => $data['shipping_address'],
                ]);

                $orderTotal = 0;

                // Get product IDs and quantities from the request data
                $productsData = $data['products'];

                // Fetch all active products in the order from the database
                $productIds = collect($productsData)->pluck('product_id');
                $products = Product::whereIn('id', $productIds)->where('status', 'active')->get();

                Log::info('products: ' . $products);


                // Attach products to the order and calculate the total
                foreach ($productsData as $productData) {
                    $product = $products->firstWhere('id', $productData['product_id']);

                    if ($product) {
                        OrderProduct::create([
                            'order_id' => $order->id,
                            'product_id' => $product->id,
                            'quantity' => $productData['quantity'],
                            'price' => $product->price,
                            'store_id' => $product->store_id,
                        ]);
                        $this->deductProductQuantities($product, $productData['quantity']);
                        $orderTotal += $product->price * $productData['quantity'];
                    }
                }
//                Log::info('orderTotal: ' . $order);

                // Update the order total and refresh the order instance
                $order->update(['order_total' => $orderTotal]);
                $order->refresh();
                event(new OrderCreated($order));
                // Return the order instance from the transaction block
                return $order;
            });

            return $order;
            // Fire the OrderCreated event outside the transaction


        } catch (\Exception $e) {
            // Rethrow the exception with a custom message
            throw new \Exception( $e->getMessage());
        }

        // Return the order to the user

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


    public function deductProductQuantities(Product $product, $quantity): void
    {

        if ($product->quantity < $quantity) {
            throw new \Exception('Insufficient quantity for product ' . $product->name);
        }

        $remainingQuantity = $product->quantity - $quantity;
        $product->update(['quantity' => $remainingQuantity]);
        if ($remainingQuantity < 2) {
            $product->update(['status' => 'out_of_stock']);
        }

        // Deduct quantities for each product in the order

    }
}
