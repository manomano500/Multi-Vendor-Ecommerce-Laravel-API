<?php

namespace Database\Factories;

use App\Models\City;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<City>
 */
class CityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            $cities = [
                "Tripoli",
                "Benghazi",
                "Misrata",
                "Tarhuna",
                "Zawiya",
                "Zliten",
                "Sabha",
                "Sirte",
                "Al Marj",
                "Derna",
                "Zintan",
                "Al Khums",
                "Az Zawiyah",
                "Al Ajaylat",
                "Gharyan",
                "Al Abyar",
                "Tobruk",


            ]


            //
        ];
    }
}
