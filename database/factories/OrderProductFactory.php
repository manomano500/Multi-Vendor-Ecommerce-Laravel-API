<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class OrderProductFactory extends Factory
{
    protected $model = OrderProduct::class;

    public function definition(): array
    {
        $products = Product::where('status' ,'=','active')->limit(10)->get();
        $stores =[];
            foreach ($products as $product) {

            }
        return [
            'order_id' => Order::factory()->create()->id,
            'product_id' => Product::factory()->create([
                'status' => 'active',

            ])->id,
            'quantity' => 2,
            'price' => 20,
            'store_id' => Store::wherefirstWhere('status','=','active'), // 'store_id' is added to the definition array


//            'created_at' => Carbon::now(),
//            'updated_at' => Carbon::now(),
        ];
    }
}
