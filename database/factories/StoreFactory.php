<?php

namespace Database\Factories;

use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class StoreFactory extends Factory
{
    protected $model = Store::class;

    public function definition(): array
    {
        $user = User::factory()->create(['role_id' => 2]);

        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'name' => $this->faker->company(),
            'description' => $this->faker->text(),
            'category_id' => $this->faker->randomElement([1, 5, 9, 13, 17]),
            'user_id' => $user->id, // Add this line
            'image' => $this->faker->imageUrl,
            'status' => $this->faker->randomElement(['active', 'inactive']),
            'address' => $this->faker->address(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->email(),

        ];
    }
}
