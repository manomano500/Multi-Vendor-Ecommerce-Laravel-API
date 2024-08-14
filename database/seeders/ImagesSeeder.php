<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ImagesSeeder extends Seeder
{

    public function run()
    {
        // Fetch all image files from the data folder
        $images = array_diff(scandir(public_path('storage/data/bags')), ['.', '..']);

        $productIds = Product::all()->pluck('id')->toArray(); // Get all product IDs

        foreach ($images as $image) {
            // Generate random product ID for each image
            $productId = $productIds[array_rand($productIds)];

            // Create a ProductImage record for each image
            ProductImage::create([
                'product_id' => $productId,
                'image' => 'storage/data/bags/' . $image
            ]);
        }
    }
}
