<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Store;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    public function run(): void
    {


$stores =Store::factory()->count(100)->create();

        $stores->each(function ($store) {
            Product::factory()->count(100)->create([
                'store_id' => $store->id,
            ]);
        });

    }
}
