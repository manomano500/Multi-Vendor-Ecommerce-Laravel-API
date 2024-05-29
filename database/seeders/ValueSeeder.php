<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ValueSeeder extends Seeder
{
    public function run(): void
    {
        $values = [
           ['name' => 'Red'],
              ['name' => 'Blue'],
            ['name'=>'Green'],
            ['name'=>'Yellow'],
            ['name'=>'Black'],
            ['name'=>'White'],
            ['name'=>'Pink'],
            ['name'=>'Purple'],
            ['name'=>'Orange'],
            ['name'=>'Grey'],

          ['name'=>'s'],
            ['name'=>'m'],
            ['name'=>'l'],
            ['name'=>'xl'],
            ['name'=>'xxl'],
            ['name'=>'xxxl'],
            ['name'=>'xxxxl'],
            ['name'=>'xxxxxl'],
            ['name'=>'xxxxxx'],
            ['name'=>'10cm'],
            ['name'=>'20cm'],
            ['name'=>'30cm'],



            ['name'=>'120cm'],
            ['name'=>'130cm'],
            ['name'=>'140cm'],
            ['name'=>'150cm'],
            ['name'=>'160cm'],
            ['name'=>'170cm'],

            ['name'=>'10kg'],
            ['name'=>'20kg'],
            ['name'=>'30kg'],
            ['name'=>'40kg'],
            ['name'=>'50kg'],
            ['name'=>'60kg'],
            ['name'=>'70kg'],
            ['name'=>'80kg'],
            ['name'=>'90kg'],
            ['name'=>'100kg'],
            ['name'=>'110kg'],
            ['name'=>'120kg'],
            ['name'=>'130kg'],
            ['name'=>'140kg'],
            ['name'=>'150kg'],
            ['name'=>'160kg'],
            ['name'=>'170kg'],
            ['name'=>'180kg'],
            ['name'=>'190kg'],
            ['name'=>'200kg'],
            ['name'=>'210kg'],
            ['name'=>'220kg'],
            ['name'=>'230kg'],
            ['name'=>'240kg'],
            ['name'=>'250kg'],
            ['name'=>'260kg'],
            ['name'=>'270kg'],
            ['name'=>'280kg'],
            ['name'=>'290kg'],
            ['name'=>'300kg'],
            ['name'=>'310kg'],
            ['name'=>'320kg'],
            ['name'=>'330kg'],
            ['name'=>'340kg'],
            ['name'=>'350kg'],
            ['name'=>'360kg'],
            ['name'=>'370kg'],
            ['name'=>'380kg'],
            ['name'=>'390kg'],
            ['name'=>'400kg'],
            ['name'=>'410kg'],
            ['name'=>'420kg'],
            ['name'=>'430kg'],
            ['name'=>'440kg'],
            ['name'=>'450kg'],
            ['name'=>'460kg'],
            ['name'=>'470kg'],
            ['name'=>'480kg'],
            ['name'=>'490kg'],
            ['name'=>'500kg'],
            ['name'=>'510kg'],
            ['name'=>'520kg'],
            ['name'=>'530kg'],
            ['name'=>'540kg'],
            ['name'=>'550kg'],
            ['name'=>'560kg'],
            ['name'=>'570kg'],
            ['name'=>'580kg'],
            ['name'=>'590kg'],
            ['name'=>'600kg'],
            ['name'=>'610kg'],
            ['name'=>'620kg'],
            ['name'=>'630kg'],
            ['name'=>'640kg'],
            ['name'=>'650kg'],
            ['name'=>'660kg'],
            ['name'=>'670kg'],
            ['name'=>'680kg'],
            ['name'=>'690kg'],
            ['name'=>'700kg'],
            ['name'=>'710kg'],
            ['name'=>'720kg'],
            ['name'=>'adidas'],
            ['name'=>'nike'],
            ['name'=>'puma'],
            ['name'=>'reebok'],
            ['name'=>'new balance'],
            ['name'=>'asics'],
            ['name'=>'fila'],
            ['name'=>'under armour'],
            ['name'=>'vans'],
            ['name'=>'converse'],
            ['name'=>'jordan'],
            ['name'=>'gucci'],
            ['name'=>'balenciaga'],
            ['name'=>'louis vuitton'],









            ];

        foreach ($values as $value) {
            \App\Models\Value::firstOrCreate($value);
        }
    }
}
