<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    public function run(): void
    {
\DB::table('stores')->insert([
            'name' => 'John Doe',
            'description' => 'ewr',
            'category_id' => 1,
            'user_id' => 2,
            'image' => 'ewr',
            'status' => 'active',
    'address' => 'ewr',

    'city_id' => 1,
        ]);

    }
}
