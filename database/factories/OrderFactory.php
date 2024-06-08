<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'user_id' => 3,
            'order_total' => 40,
            'status' => 'pending',
            'city' => $this->faker->city,
            'shipping_address' => $this->faker->address,

            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
