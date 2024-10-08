<?php

namespace Database\Factories;

use App\Models\Category;
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
            'name' => 'placeholder name',
            'description' => $this->faker->text(),
            'category_id' => Category::factory()->create()->id,
            'user_id' => $user->id, // Add this line
            'image' => $this->faker->imageUrl,
            'status' => $this->faker->randomElement(['active', 'inactive']),
            'address' => $this->faker->address(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->email(),

        ];
    }
}
