<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ValueSeeder extends Seeder
{
    public function run(): void
    {
        $colorAttribute = \App\Models\Attribute::where('name', 'Color')->first();
       $sizeAttribute = \App\Models\Attribute::where('name', 'Size')->first();
       $materialAttribute = \App\Models\Attribute::where('name', 'Material')->first();
         $brandAttribute = \App\Models\Attribute::where('name', 'Brand')->first();
            $weightAttribute = \App\Models\Attribute::where('name', 'Weight')->first();
            $heightAttribute = \App\Models\Attribute::where('name', 'Height')->first();
            $widthAttribute = \App\Models\Attribute::where('name', 'Width')->first();
            $lengthAttribute = \App\Models\Attribute::where('name', 'Length')->first();

        $values = [
            ['name' => 'Red', 'attribute_id' => $colorAttribute->id],
            ['name' => 'Blue', 'attribute_id' => $colorAttribute->id],
            ['name' => 'Green', 'attribute_id' => $colorAttribute->id],
            ['name' => 'Yellow', 'attribute_id' => $colorAttribute->id],
            ['name' => 'Black', 'attribute_id' => $colorAttribute->id],
            ['name' => 'White', 'attribute_id' => $colorAttribute->id],
            ['name' => 'S', 'attribute_id' => $sizeAttribute->id],
            ['name' => 'M', 'attribute_id' => $sizeAttribute->id],
            ['name' => 'L', 'attribute_id' => $sizeAttribute->id],
            ['name' => 'XL', 'attribute_id' => $sizeAttribute->id],
            ['name' => 'Cotton', 'attribute_id' => $materialAttribute->id],
            ['name' => 'Polyester', 'attribute_id' => $materialAttribute->id],
            ['name' => 'Leather', 'attribute_id' => $materialAttribute->id],
            ['name' => 'Nike', 'attribute_id' => $brandAttribute->id],
            ['name' => 'Adidas', 'attribute_id' => $brandAttribute->id],
            ['name' => 'Puma', 'attribute_id' => $brandAttribute->id],
            ['name' => '1kg', 'attribute_id' => $weightAttribute->id],
            ['name' => '2kg', 'attribute_id' => $weightAttribute->id],
            ['name' => '3kg', 'attribute_id' => $weightAttribute->id],
            ['name' => '4kg', 'attribute_id' => $weightAttribute->id],
            ['name' => '5kg', 'attribute_id' => $weightAttribute->id],
            ['name' => '6kg', 'attribute_id' => $weightAttribute->id],
            ['name' => '7kg', 'attribute_id' => $weightAttribute->id],
            ['name' => '8kg', 'attribute_id' => $weightAttribute->id],
            ['name' => '9kg', 'attribute_id' => $weightAttribute->id],
            ];

        foreach ($values as $value) {
            \App\Models\Value::firstOrCreate($value);
        }
    }
}
