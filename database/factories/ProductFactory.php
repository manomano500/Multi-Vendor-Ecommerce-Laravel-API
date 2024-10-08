<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'description' => $this->faker->text,
            'price' => $this->faker->randomFloat(2, 1, 100),
            'status' => "active",
//            'status' => $this->faker->randomElement(['active', 'out_of_stock']),
            'quantity' => $this->faker->numberBetween(1, 100),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'store_id' => Store::factory()->create()->id,

            'category_id' => Category::get()->random()->id,



        ];
    }
}
