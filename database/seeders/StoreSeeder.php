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
use Illuminate\Support\Facades\Storage;
use Exception;
use Faker\Factory as Faker;

class StoreSeeder extends Seeder
{
    protected $jsonData;

    public function run(): void
    {
        $faker = Faker::create();

        // Path to the 'data' directory
        $dataPath = database_path('data/fakestore_data');

        // Load JSON data
        $jsonFilePath = $dataPath . '/fakestore_data.json';
        if (!File::exists($jsonFilePath)) {
            throw new Exception("File does not exist at path: " . $jsonFilePath);
        }
        $this->jsonData = json_decode(File::get($jsonFilePath), true);

        if (!$this->jsonData) {
            throw new Exception("Failed to parse JSON data from file: " . $jsonFilePath);
        }

        // Extract categories from JSON data
        $categoriesData = $this->jsonData['categories'] ?? [];
        if (empty($categoriesData)) {
            throw new Exception("No categories data found in JSON file.");
        }

        // Create categories
        $categories = $this->createCategories($categoriesData);

        // Create 20 stores and products
        $this->createStoresAndProducts($categories, $dataPath);
    }

    protected function createCategories(array $categoriesData): array
    {
        $categories = [];
        foreach ($categoriesData as $categoryData) {
            $category = Category::firstOrCreate([
                'name->en' => $categoryData['name'],
                'name->ar' => $this->translateToArabic($categoryData['name']),
            ]);
            $categories[$categoryData['name']] = $category;
        }
        return $categories;
    }

    protected function createStoresAndProducts(array $categories, string $dataPath): void
    {
        $faker = Faker::create();

        // Track used images
        $usedImages = Store::pluck('image')->toArray();

        // Get a list of all images in the store_images folder
        $imageFiles = File::files($dataPath . '/store_images');

        for ($i = 1; $i <= 20; $i++) {
            // Create a store
            $storeName = "Store $i";

            $user = User::factory()->create(['role_id' => 2]);
            $category = $categories[array_rand($categories)];

            // Filter out used images
            $availableImages = array_filter($imageFiles, function ($image) use ($usedImages) {
                return !in_array('images/stores/' . $image->getFilename(), $usedImages);
            });

            if (empty($availableImages)) {
                throw new Exception("No available images left for stores.");
            }

            // Pick a random image from the available ones
            $imageFile = $faker->randomElement($availableImages);
            $imagePath = $imageFile->getPathname();

            // Store the image
            $storedImagePath = $this->storeImage($imagePath, $storeName, 'stores');
            $usedImages[] = $storedImagePath;

            $store = Store::create([
                'name' => $storeName,
                'description' => $faker->sentence,
                'user_id' => $user->id,
                'category_id' => $category->id,
                'image' => $storedImagePath,
                'address' => $faker->address,
                'status' => 'active',
            ]);

            // Create 20 products for this store
            $this->createProductsForStore($store, $categories, $dataPath);
        }
    }

    protected function createProductsForStore(Store $store, array $categories, string $dataPath): void
    {
        $faker = Faker::create();
        $productsData = $this->jsonData['products'] ?? [];

        for ($i = 1; $i <= 20; $i++) {
            $productData = $productsData[array_rand($productsData)];
            $categoryName = $productData['category'];
            $category = $categories[$categoryName] ?? null;

            if (!$category) {
                $this->command->warn("Category not found for product: " . $productData['title']);
                continue;
            }

            // Handle multiple images
            $imagePaths = $productData['images'] ?? [];
            $storedImagePaths = [];
            foreach ($imagePaths as $imageFile) {
                $imagePath = $dataPath . '/' . $imageFile;
                if (File::exists($imagePath)) {
                    $storedImagePath = $this->storeImage($imagePath, $productData['title'], 'products');
                    $storedImagePaths[] = $storedImagePath;
                } else {
                    $this->command->warn("Image not found: " . $imagePath);
                }
            }

            // Create the product
            $product = Product::create([
                'name' => $faker->word,
                'description' => $productData['description'] ?? $faker->sentence,
                'price' => $productData['price'] ?? $faker->randomFloat(2, 10, 1000),
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
            $this->attachVariations($product, $category);
        }
    }

    protected function attachVariations($product, $category): void
    {
        $categoryNameEn = is_array($category->name) ? $category->name['en'] : $category->name;

        $categoryData = collect($this->jsonData['categories'])->firstWhere('name', $categoryNameEn);

        if (!$categoryData) {
            $this->command->warn("Category data not found for: " . $categoryNameEn);
            return;
        }

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
            $this->command->info("No variations found for category: " . $categoryNameEn);
        }
    }

    protected function storeImage(string $imagePath, string $name, string $folder): string
    {
        if (!File::exists($imagePath)) {
            throw new Exception("Image file not found: " . $imagePath);
        }

        $extension = pathinfo($imagePath, PATHINFO_EXTENSION);
        $filename = Str::slug($name) . '-' . now()->timestamp . '.' . $extension;
        $newPath = "images/{$folder}/" . $filename;

        Storage::disk('public')->put($newPath, File::get($imagePath));

        return $newPath;
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
            'pharmacy' => 'صيدلية',
        ];
        return $translations[strtolower($name)] ?? $name;
    }
}
