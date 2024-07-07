<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

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
        ];

        foreach ($cities as $city) {
            City::create([
                'name' => $city,
            ]);
        }
    }
}
