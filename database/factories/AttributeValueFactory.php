<?php

namespace Database\Factories;

use App\Models\AttributeValue;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class AttributeValueFactory extends Factory
{
    protected $model = AttributeValue::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
