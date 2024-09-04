<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $userIds =User::where('role_id',3)->pluck('id')->toArray();
        return [
//            "city"=> "City fewrweName",
//  "shipping_address"=> "123 Main St",
//  "phone" =>"93493",
            'user_id' => User::factory()->create([
                'role_id' => 3,
            ])->id,
            'status' => 'pending',
//            'order_total' => 33,
            'payment_method' => 'pay_on_deliver',


            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
