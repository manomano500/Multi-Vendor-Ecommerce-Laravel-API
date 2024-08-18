<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Store;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use App\Models\Variation;
use App\Models\Attribute;
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

        $storeCount = 1;

        foreach ($jsonData['categories'] as $categoryData) {
            // Create category
            $category = Category::firstOrCreate([
                'name' => [
                    'en' => $categoryData['name'],
                    'ar' => $this->translateToArabic($categoryData['name'])
                ],
            ]);

            // Create a new user for the store
            $user = User::factory()->create(['role_id' => 2]);

            // Create a store for each category
            $store = Store::create([
                'name' => 'Store ' . $storeCount,
                'description' => 'Description for Store ' . $storeCount,
                'user_id' => $user->id,
                'category_id' => $category->id,
                'image' => $faker->imageUrl($width = 640, $height = 480, 'business', true),
                'address' => '123 Main St',
                'status' => 'active',
            ]);

            // Filter products for this category from JSON data
            $products = array_filter($jsonData['products'], function ($product) use ($categoryData) {
                return $product['category'] === $categoryData['name'];
            });

            if (empty($products)) {
                $this->command->info("No products found for category: " . $categoryData['name']);
            }

            foreach ($products as $productData) {
                try {
                    // Handle multiple images
                    $imagePaths = $productData['images'] ?? []; // Handle cases where 'images' might be missing
                    $storedImagePaths = [];
                    foreach ($imagePaths as $index => $imageUrl) {
                        $imagePath = $dataPath . '/' . $categoryData['folder'] . '/' . basename($imageUrl);
                        if (File::exists($imagePath)) {
                            $storedImagePath = $this->storeImage($imagePath, $productData['title'], $index);
                            $storedImagePaths[] = $storedImagePath;
                        } else {
                            $this->command->warn("Image not found: " . $imagePath);
                        }
                    }

                    // Create product
                    $product = Product::create([
                        'name' => $productData['title'],
                        'description' => $productData['description'] ?? $productData['title'], // Fallback to title if description is missing
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

                    // Attach variations based on category
                    if (isset($categoryData['variations'])) {
                        foreach ($categoryData['variations'] as $variationData) {
                            $attribute = Attribute::firstOrCreate(['name' => $variationData['type']]);

                            foreach ($variationData['options'] as $option) {
                                $variation = Variation::firstOrCreate([
                                    'attribute_id' => $attribute->id,
                                    'value' => $option,
                                ]);

                                // Attach the variation to the product
                                $product->variations()->attach($variation->id);
                            }
                        }
                    } else {
                        $this->command->info("No variations found for category: " . $categoryData['name']);
                    }

                } catch (Exception $e) {
                    // Handle exception
                    $this->command->error("Failed to store image or create product: " . $e->getMessage());
                }
            }

            $storeCount++;
        }
    }

    protected function storeImage(string $imagePath, string $productName, int $index): string
    {
        if (!File::exists($imagePath)) {
            throw new Exception("Image file not found: " . $imagePath);
        }

        $imageContent = File::get($imagePath);
        $filename = Str::slug($productName) . '-' . ($index + 1) . '.' . pathinfo($imagePath, PATHINFO_EXTENSION);
        $path = storage_path('app/public/images/products/' . $filename);

        File::put($path, $imageContent);

        return 'images/products/' . $filename;
    }

    protected function translateToArabic(string $name): string
    {
        $translations = [
            'women' => 'نساء',
            'men' => 'رجال',
            'kids' => 'أطفال',
            'beauty' => 'جمال',
            'fragrances' => 'عطور',
            'furniture' => 'أثاث',
            'groceries' => 'بقالة',
            'electronics' => 'إلكترونيات',
            'jewelery' => 'مجوهرات',
            "men's clothing" => 'ملابس الرجال',
            'shoes' => 'أحذية',
        ];
        return $translations[strtolower($name)] ?? $name;
    }
}
