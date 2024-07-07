<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\Variation;
use Illuminate\Database\Seeder;

class VariationSeeder extends Seeder
{
    public function run(): void
    {
        $colorAttribute = Attribute::where('name', 'Color')->first();
       $sizeAttribute = Attribute::where('name', 'Size')->first();
       $materialAttribute = Attribute::where('name', 'Material')->first();
         $brandAttribute = Attribute::where('name', 'Brand')->first();
            $weightAttribute = Attribute::where('name', 'Weight')->first();
            $heightAttribute = Attribute::where('name', 'Height')->first();
            $widthAttribute = Attribute::where('name', 'Width')->first();
            $lengthAttribute = Attribute::where('name', 'Length')->first();

        $values = [
            ['value' => 'Red', 'attribute_id' => $colorAttribute->id],
            ['value' => 'Blue', 'attribute_id' => $colorAttribute->id],
            ['value' => 'Green', 'attribute_id' => $colorAttribute->id],
            ['value' => 'Yellow', 'attribute_id' => $colorAttribute->id],
            ['value' => 'Black', 'attribute_id' => $colorAttribute->id],
            ['value' => 'White', 'attribute_id' => $colorAttribute->id],
            ['value' => 'S', 'attribute_id' => $sizeAttribute->id],
            ['value' => 'M', 'attribute_id' => $sizeAttribute->id],
            ['value' => 'L', 'attribute_id' => $sizeAttribute->id],
            ['value' => 'XL', 'attribute_id' => $sizeAttribute->id],
            ['value' => 'Cotton', 'attribute_id' => $materialAttribute->id],
            ['value' => 'Polyester', 'attribute_id' => $materialAttribute->id],
            ['value' => 'Leather', 'attribute_id' => $materialAttribute->id],
            ['value' => 'Nike', 'attribute_id' => $brandAttribute->id],
            ['value' => 'Adidas', 'attribute_id' => $brandAttribute->id],
            ['value' => 'Puma', 'attribute_id' => $brandAttribute->id],
            ['value' => '1kg', 'attribute_id' => $weightAttribute->id],
            ['value' => '2kg', 'attribute_id' => $weightAttribute->id],
            ['value' => '3kg', 'attribute_id' => $weightAttribute->id],
            ['value' => '4kg', 'attribute_id' => $weightAttribute->id],
            ['value' => '5kg', 'attribute_id' => $weightAttribute->id],
            ['value' => '6kg', 'attribute_id' => $weightAttribute->id],
            ['value' => '7kg', 'attribute_id' => $weightAttribute->id],
            ['value' => '8kg', 'attribute_id' => $weightAttribute->id],
            ['value' => '9kg', 'attribute_id' => $weightAttribute->id],
            ];

        foreach ($values as $value) {
            Variation::firstOrCreate($value);
        }
    }
}
