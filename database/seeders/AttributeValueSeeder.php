<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Variation;
use Illuminate\Database\Seeder;

class AttributeValueSeeder extends Seeder
{
    public function run(): void
    {
        $attributes = [
            Attribute::where('name', 'Color')->first(),
            Attribute::where('name', 'Size')->first(),
            Attribute::where('name', 'Weight')->first(),
            Attribute::where('name', 'Brand')->first(),

        ];
        $colorValues = [
            Variation::where('name', 'Red')->first(),
            Variation::where('name', 'Blue')->first(),
            Variation::where('name', 'Green')->first(),
            Variation::where('name', 'Yellow')->first(),
            Variation::where('name', 'Black')->first(),
            Variation::where('name', 'White')->first(),
            Variation::where('name', 'Pink')->first(),
            Variation::where('name', 'Purple')->first(),
            Variation::where('name', 'Orange')->first(),
            Variation::where('name', 'Grey')->first(),


        ];

        $sizeValues = [
            Variation::where('name', 's')->first(),
            Variation::where('name', 'm')->first(),
            Variation::where('name', 'l')->first(),
            Variation::where('name', 'xl')->first(),
            Variation::where('name', 'xxl')->first(),
            Variation::where('name', 'xxxl')->first(),
            Variation::where('name', 'xxxxl')->first(),
            Variation::where('name', 'xxxxxl')->first(),
            Variation::where('name', 'xxxxxx')->first(),
            Variation::where('name', '10cm')->first(),
            Variation::where('name', '20cm')->first(),
            Variation::where('name', '30cm')->first(),
            Variation::where('name', '120cm')->first(),
            Variation::where('name', '130cm')->first(),
            Variation::where('name', '140cm')->first(),
            Variation::where('name', '150cm')->first(),
            Variation::where('name', '160cm')->first(),
            Variation::where('name', '170cm')->first(),

        ];

        $weightValues = [
            Variation::where('name', '10kg')->first(),
            Variation::where('name', '20kg')->first(),
            Variation::where('name', '30kg')->first(),
            Variation::where('name', '40kg')->first(),
            Variation::where('name', '50kg')->first(),
            Variation::where('name', '60kg')->first(),
            Variation::where('name', '70kg')->first(),
            Variation::where('name', '80kg')->first(),
            Variation::where('name', '90kg')->first(),
            Variation::where('name', '100kg')->first(),
            Variation::where('name', '110kg')->first(),
            Variation::where('name', '120kg')->first(),
            Variation::where('name', '130kg')->first(),
            Variation::where('name', '140kg')->first(),
            Variation::where('name', '150kg')->first(),
            Variation::where('name', '160kg')->first(),

        ];
        $brandValues = [
            Variation::where('name', 'adidas')->first(),
            Variation::where('name', 'nike')->first(),
            Variation::where('name', 'puma')->first(),
            Variation::where('name', 'reebok')->first(),
            Variation::where('name', 'new balance')->first(),
            Variation::where('name', 'asics')->first(),
            Variation::where('name', 'fila')->first(),
            Variation::where('name', 'under armour')->first(),
            Variation::where('name', 'vans')->first(),
            Variation::where('name', 'converse')->first(),
            Variation::where('name', 'jordan')->first(),
            Variation::where('name', 'gucci')->first(),
            Variation::where('name', 'balenciaga')->first(),
            Variation::where('name', 'louis vuitton')->first(),
        ];

        foreach ($colorValues as $colorValue) {
            AttributeValue::create([
                'attribute_id' => $attributes[0]->id,
                'value_id' => $colorValue->id
            ]);
        }

        foreach ($sizeValues as $sizeValue) {
            AttributeValue::create([
                'attribute_id' => $attributes[1]->id,
                'value_id' => $sizeValue->id
            ]);
        }
        foreach ($weightValues as $weightValue) {
            AttributeValue::create([
                'attribute_id' => $attributes[2]->id,
                'value_id' => $weightValue->id
            ]);
        }
        foreach ($brandValues as $brandValue) {
            AttributeValue::create([
                'attribute_id' => $attributes[3]->id,
                'value_id' => $brandValue->id
            ]);
        }



    }


    }


