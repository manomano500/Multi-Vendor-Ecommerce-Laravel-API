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

class EcommerceSeeder extends Seeder
{
    protected $jsonData;

    public function run(): void
    {
        $faker = Faker::create();

        // Path to the 'data' directory
        $dataPath = database_path('data/fakestore_data');

        // Load JSON data
        $jsonFilePath = $dataPath . '/fakestore_data.json';
        $this->jsonData = json_decode(File::get($jsonFilePath), true);

        if (!$this->jsonData) {
            throw new Exception("Failed to parse JSON data from file: " . $jsonFilePath);
        }

        // Load stores JSON data
        $storesJsonFilePath = $dataPath . '/stores.json';
        $storesData = json_decode(File::get($storesJsonFilePath), true);

        if (!$storesData) {
            throw new Exception("Failed to parse JSON data from file: " . $storesJsonFilePath);
        }

        // Create categories once
        $categories = $this->createCategories($this->jsonData['categories']);

        // Seed stores and products multiple times
        for ($i = 0; $i < 1; $i++) {
            // Create stores
            $stores = $this->createStores($storesData['stores'], $categories);

            // Create products and associate with stores
            $this->createProducts($this->jsonData['products'], $categories, $stores, $dataPath);
        }
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

    protected function createStores(array $storesData, array $categories): array
    {
        $stores = [];
        foreach ($storesData as $storeData) {
            $this->command->info("Processing store: " . $storeData['name']);

            $user = User::factory()->create(['role_id' => 2]);

            $categoryName = $storeData['category'];
            $category = $categories[$categoryName] ?? null;

            if (!$category) {
                $this->command->warn("Category not found for store: " . $storeData['name']);
                continue;
            }

            $imagePath = database_path('data/fakestore_data/' . $storeData['image']);
            $storedImagePath = null;
            if (File::exists($imagePath)) {
                $storedImagePath = $this->storeImage($imagePath, $storeData['name'], 'stores');
            } else {
                $this->command->warn("Image not found for store: " . $storeData['name']);
            }

            $store = Store::create([
                'name' => $storeData['name'],
                'description' => $storeData['description'],
                'user_id' => $user->id,
                'category_id' => $category->id,
                'image' => $storedImagePath,
                'address' => 'address',
                'status' => 'active',
            ]);

            $stores[$store->name] = $store;
        }
        return $stores;
    }

    protected function createProducts(array $productsData, array $categories, array $stores, string $dataPath): void
    {
        foreach ($productsData as $productData) {
            try {
                $categoryName = $productData['category'];
                $category = $categories[$categoryName] ?? null;

                if (!$category) {
                    $this->command->warn("Category not found for product: " . $productData['title']);
                    continue;
                }

                // Find a store for this category
                $store = collect($stores)->first(function ($store) use ($category) {
                    return $store->category_id === $category->id;
                });

                if (!$store) {
                    $this->command->warn("No store found for category: " . $categoryName);
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

                // Create product
                $product = Product::create([
                    'name' => $productData['title'],
                    'description' => $productData['description'] ?? $productData['title'],
                    'price' => rand(1,20 ),
                    'quantity' => rand(1, 5),
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

            } catch (Exception $e) {
                $this->command->error("Failed to create product: " . $e->getMessage());
            }
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
