<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'slug' => $this->faker->text,
            'price' => $this->faker->randomFloat(2, 1, 100),
            'qty' => $this->faker->numberBetween(1, 100),
            'status' => $this->faker->boolean,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'store_id' => 1,
            'category_id' => 1,

            'thumb_image' => 'https://via.placeholder.com/150',


        ];
    }
}
