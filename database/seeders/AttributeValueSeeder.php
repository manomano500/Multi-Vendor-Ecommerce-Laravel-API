<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Value;
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
            Value::where('name', 'Red')->first(),
            Value::where('name', 'Blue')->first(),
            Value::where('name', 'Green')->first(),
            Value::where('name', 'Yellow')->first(),
            Value::where('name', 'Black')->first(),
            Value::where('name', 'White')->first(),
            Value::where('name', 'Pink')->first(),
            Value::where('name', 'Purple')->first(),
            Value::where('name', 'Orange')->first(),
            Value::where('name', 'Grey')->first(),


        ];

        $sizeValues = [
            Value::where('name', 's')->first(),
            Value::where('name', 'm')->first(),
            Value::where('name', 'l')->first(),
            Value::where('name', 'xl')->first(),
            Value::where('name', 'xxl')->first(),
            Value::where('name', 'xxxl')->first(),
            Value::where('name', 'xxxxl')->first(),
            Value::where('name', 'xxxxxl')->first(),
            Value::where('name', 'xxxxxx')->first(),
            Value::where('name', '10cm')->first(),
            Value::where('name', '20cm')->first(),
            Value::where('name', '30cm')->first(),
            Value::where('name', '120cm')->first(),
            Value::where('name', '130cm')->first(),
            Value::where('name', '140cm')->first(),
            Value::where('name', '150cm')->first(),
            Value::where('name', '160cm')->first(),
            Value::where('name', '170cm')->first(),

        ];

        $weightValues = [
            Value::where('name', '10kg')->first(),
            Value::where('name', '20kg')->first(),
            Value::where('name', '30kg')->first(),
            Value::where('name', '40kg')->first(),
            Value::where('name', '50kg')->first(),
            Value::where('name', '60kg')->first(),
            Value::where('name', '70kg')->first(),
            Value::where('name', '80kg')->first(),
            Value::where('name', '90kg')->first(),
            Value::where('name', '100kg')->first(),
            Value::where('name', '110kg')->first(),
            Value::where('name', '120kg')->first(),
            Value::where('name', '130kg')->first(),
            Value::where('name', '140kg')->first(),
            Value::where('name', '150kg')->first(),
            Value::where('name', '160kg')->first(),

        ];
        $brandValues = [
            Value::where('name', 'adidas')->first(),
            Value::where('name', 'nike')->first(),
            Value::where('name', 'puma')->first(),
            Value::where('name', 'reebok')->first(),
            Value::where('name', 'new balance')->first(),
            Value::where('name', 'asics')->first(),
            Value::where('name', 'fila')->first(),
            Value::where('name', 'under armour')->first(),
            Value::where('name', 'vans')->first(),
            Value::where('name', 'converse')->first(),
            Value::where('name', 'jordan')->first(),
            Value::where('name', 'gucci')->first(),
            Value::where('name', 'balenciaga')->first(),
            Value::where('name', 'louis vuitton')->first(),
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


