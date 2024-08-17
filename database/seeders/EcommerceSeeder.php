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

        // Get folder names as categories
        $folderNames = array_filter(File::directories($dataPath), function ($folder) {
            return is_dir($folder);
        });

        // Load JSON data
        $jsonFilePath = $dataPath . '/fakestore_data.json';
        $jsonData = json_decode(File::get($jsonFilePath), true);

        $storeCount = 1;

        foreach ($folderNames as $folderPath) {
            $folderName = basename($folderPath);

            // Create category
/*            $category = Category::firstOrCreate(['name' => $folderName]);*/
            $category = Category::firstOrCreate([
                'name' => [
                    'en' => $folderName,             // Store the folder name in English
                    'ar' => $this->translateToArabic($folderName) // Store the folder name in Arabic
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
                'image' => $faker->imageUrl($width = 640, $height = 480, 'business', true), // Generate a fake image URL
                'address' => '123 Main St',
                'status' => 'active',
            ]);

            // Filter products for this category from JSON data
            $products = array_filter($jsonData['products'], function ($product) use ($folderName) {
                return $product['category'] === $folderName;
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
                        $imagePath = $dataPath . '/' . $folderName . '/' . basename($imageUrl);
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

    /**
     * Translate the category name to Arabic.
     *
     * @param string $name
     * @return string
     */
    protected function translateToArabic(string $name): string
    {
        // Implement a translation method here
        // You could use a translation API or a manual mapping of known category names
        // Example:
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
