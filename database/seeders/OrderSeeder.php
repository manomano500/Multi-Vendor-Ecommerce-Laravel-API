<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderSeeder extends Seeder
{
    public function run()
    {
        // Create 60 orders
        $orders = Order::factory(20)->create();

        // Number of stores and products to fetch
        $numberOfStores = 4;
        $numberOfProductsPerStore = 5;

        foreach ($orders as $order) {
            // Fetch random stores
            $stores = Store::inRandomOrder()->take($numberOfStores)->get();

            foreach ($stores as $store) {
                // Fetch random products from each store
                $products = Product::where('store_id', $store->id)
                    ->where('status', 'active')
                    ->whereNull('deleted_at')
                    ->inRandomOrder()
                    ->take($numberOfProductsPerStore)
                    ->get();

                foreach ($products as $product) {
                    $orderProduct = OrderProduct::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'store_id' => $product->store_id,
                        'status' => 'Pending',
                        'quantity' => rand(1, 9),
                        'price' => $product->price,
                    ]);

                    // Update the price based on quantity
//                    $orderProduct->price = $orderProduct->quantity * $orderProduct->product->price;
//                    $orderProduct->save();
                }
            }

            // Calculate and save the total order price
            $order->order_total = $order->orderProducts->sum('price');
            $order->status = 'pending';
            Log::info($order);
            $order->save();
        }
    }
}
