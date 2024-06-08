<?php

namespace Database\Factories;

use App\Models\OrderProduct;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class OrderProductFactory extends Factory
{
    protected $model = OrderProduct::class;

    public function definition(): array
    {
        return [
            'order_id' => $this->faker->numberBetween(1, 2),
            'product_id' => $this->faker->numberBetween(1, 20),
            'quantity' => 2,
            'price' => 20,

            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
