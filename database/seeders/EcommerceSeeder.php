<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Store;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Exception;
use Faker\Factory as Faker;

class EcommerceSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // Path to the 'data' directory
        $dataPath = database_path('data/fakestore_data');

        // Load JSON data
        $jsonFilePath = $dataPath . '/fakestore_data.json';
        $jsonData = json_decode(File::get($jsonFilePath), true);

        // Track the store count
        $storeCount = 1;

        // Iterate over categories from JSON data
        foreach ($jsonData['categories'] as $categoryData) {
            $categoryName = $categoryData['name'];
            $categoryFolder = $categoryData['folder'];

            // Create or get the category
            $category = Category::firstOrCreate(['name' => $categoryName]);

            // Create multiple stores for each category
            $numberOfStores = rand(2, 5); // Adjust the number as needed
            for ($i = 0; $i < $numberOfStores; $i++) {
                // Create a new user for the store
                $user = User::factory()->create(['role_id' => 2]);

                // Create a store for the category
                $store = Store::create([
                    'name' => 'Store ' . $storeCount,
                    'description' => 'Description for Store ' . $storeCount,
                    'user_id' => $user->id,
                    'category_id' => $category->id,
                    'image' => $faker->imageUrl($width = 640, $height = 480, 'business', true), // Generate a fake image URL
                    'address' => '123 Main St',
                    'status' => 'active',
                ]);

                // Filter products for this category from JSON data
                $products = array_filter($jsonData['products'], function ($product) use ($categoryName) {
                    return $product['category'] === $categoryName;
                });

                $productCount = 1;
                foreach ($products as $productData) {
                    try {
                        $productName = $productData['title'];
                        $productDescription = $productData['description']; // Use the description from JSON

                        // Handle multiple images
                        $imagePaths = $productData['images'];
                        $storedImagePaths = [];
                        foreach ($imagePaths as $index => $imageUrl) {
                            $imagePath = $dataPath . '/' . basename($categoryFolder) . '/' . basename($imageUrl);
                            if (File::exists($imagePath)) {
                                $storedImagePath = $this->storeImage($imagePath, $productName, $index);
                                $storedImagePaths[] = $storedImagePath;
                            }
                        }

                        // Create product
                        $product = Product::create([
                            'name' => $productName,
                            'description' => $productDescription,
                            'price' => $productData['price'],
                            'quantity' => rand(1, 100),
                            'category_id' => $category->id,
                            'store_id' => $store->id,
                            'status' => 'active',
                        ]);

                        // Seed product images
                        foreach ($storedImagePaths as $storedImagePath) {
                            ProductImage::create([
                                'product_id' => $product->id,
                                'image' => $storedImagePath,
                            ]);
                        }

                        $productCount++;
                    } catch (Exception $e) {
                        // Handle exception
                        $this->command->error("Failed to store image or create product: " . $e->getMessage());
                    }
                }

                $storeCount++;
            }
        }
    }

    /**
     * Store the image and return the path.
     *
     * @param string $imagePath
     * @param string $productName
     * @param int $index
     * @return string
     */
    protected function storeImage(string $imagePath, string $productName, int $index): string
    {
        // Check if the image file exists before attempting to fetch it
        if (!File::exists($imagePath)) {
            throw new Exception("Image file not found: " . $imagePath);
        }

        $imageContent = File::get($imagePath);
        $filename = Str::slug($productName) . '-' . ($index + 1) . '.' . pathinfo($imagePath, PATHINFO_EXTENSION);
        $path = storage_path('app/public/images/products/' . $filename);

        File::put($path, $imageContent);

        return 'images/products/' . $filename;
    }
}
