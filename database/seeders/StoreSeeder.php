<?php

namespace Database\Seeders;

use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use App\Models\Store;
use App\Models\Product;

class StoreSeeder extends Seeder
{
    public function run(): void
    {
        $storeCount = 1; // Initialize the store counter

        // Create stores
        $stores = Store::factory()->count(90)->create()->each(function ($store) use (&$storeCount) {
            // Update the store with a unique name
            $store->update([
                'name' => 'St ' . $storeCount,
            ]);

            // Initialize the product counter for each store
            $productCount = 1;

            // Create products for each store
            Product::factory()->count(79)->create()->each(function ($product) use ($store, &$productCount, $storeCount) {
                // Update the product with the store_id and a unique name
                $product->update([
                    'store_id' => $store->id,
                    'name' => 'Pr ' . $productCount . ' St ' . $storeCount,
                ]);
                ProductImage::factory()->count(3)->create([
                    'product_id' => $product->id,
                ]);

                // Increment the product counter
                $productCount++;
            });

            // Increment the store counter
            $storeCount++;
        });
    }
}
