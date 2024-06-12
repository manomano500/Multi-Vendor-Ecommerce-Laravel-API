<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Store;
use App\Models\Product;

class StoreSeeder extends Seeder
{
    public function run(): void
    {
        $storeCount = 1; // Initialize the store counter

        // Create stores
        $stores = Store::factory()->count(50)->create()->each(function ($store) use (&$storeCount) {
            // Update the store with a unique name
            $store->update([
                'name' => 'Store ' . $storeCount,
            ]);

            // Initialize the product counter for each store
            $productCount = 1;

            // Create products for each store
            Product::factory()->count(50)->create()->each(function ($product) use ($store, &$productCount, $storeCount) {
                // Update the product with the store_id and a unique name
                $product->update([
                    'store_id' => $store->id,
                    'name' => 'Product ' . $productCount . ' of Store ' . $storeCount,
                ]);

                // Increment the product counter
                $productCount++;
            });

            // Increment the store counter
            $storeCount++;
        });
    }
}
