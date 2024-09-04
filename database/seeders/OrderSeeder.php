<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Store;
use App\Models\Variation;
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

                // If no products are found, skip the store
                if ($products->isEmpty()) {
                    continue;
                }

                foreach ($products as $product) {
                    // Fetch product variations
                    // Fetch product variations
                    $variations = ProductVariation::where('product_id', $product->id)
                        ->inRandomOrder()
                        ->take(rand(1, 3))
                        ->pluck('id')
                        ->toArray();
                    Log::info('Product Variations:', $variations);

                    Log::info($variations);
                    // If no variations, use an empty array
                    if (empty($variations)) {
                        $variations = [];
                    }

                    // Convert variations to a format suitable for storage
                    // Convert variations to a format suitable for storage
                    $variationsData = [];
                    foreach ($variations as $variationId) {
                        $variation = Variation::find($variationId);
                        if ($variation) {
                            $variationsData[] = [
                                'name' => $variation->attribute->name,
                                'value' => $variation->value,
                            ];
                        }
                        if (empty($variationsData)) {
                            $variationsData = [
                                ['name' => 'color', 'value' => 'Bronze'],
                                ['name' => 'size', 'value' => 'Large'],
                            ];
                        }

                    }

                    foreach ($products as $product) {
                        $orderProduct = OrderProduct::create([
                            'order_id' => $order->id,
                            'product_id' => $product->id,
//                        'store_id' => $product->store_id,
                            'status' => 'Pending',
                            'quantity' => rand(1, 9),
                            'price' => $product->price,
                            "variations" => json_encode($variationsData),
                        ]);

                        // Update the price based on quantity
//                    $orderProduct->price = $orderProduct->quantity * $orderProduct->product->price;
//                    $orderProduct->save();
                    }
                }

                // Calculate and save the total order price
//                $order->order_total = $order->orderProducts->sum('price');
                $order->status = 'pending';
                Log::info($order);
                $order->save();
            }
        }
    }
}
