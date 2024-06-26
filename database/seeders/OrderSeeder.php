<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderSeeder extends Seeder
{
    public function run()
    {
        // Create a single order
        $orders = Order::factory(60)->create();
        $products = Product::where('status', 'active')
            ->whereNull('deleted_at')
            ->whereIn('id', function ($query) {
                $query->select(DB::raw('MIN(id)'))
                    ->from('products')
                    ->where('status', 'active')
                    ->groupBy('store_id');
            })
            ->take(20)
            ->get();

        foreach ($orders as $order){
            foreach ($products as $product) {
                OrderProduct::create([
                    'order_id' => $order->id,

                    'product_id' => $product->id,
                    'store_id' => $product->store_id,
                    'status' => 'Pending',
                    'quantity' => 1,
                    'price' => 100,
                ]);
            }
        }

    }


}
