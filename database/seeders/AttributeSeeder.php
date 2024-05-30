<?php

namespace Database\Seeders;

use App\Models\Attribute;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $attributes = [
            ['name' => 'Color'],
            ['name' => 'Size'],
            ['name' => 'Material'],
            ['name' => 'Brand'],
            ['name' => 'Weight'],
            ['name' => 'Height'],
            ['name' => 'Width'],
            ['name' => 'Length']

        ];

        foreach ($attributes as $attribute) {
            Attribute::firstOrCreate($attribute);
        }
    }
        //

}
