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
            'user_id' => 3,
            'image' => 'ewr',
            'status' => 'active',
            'is_active' => 1,
            'icon' => 'ewr',
        ]);

    }
}
