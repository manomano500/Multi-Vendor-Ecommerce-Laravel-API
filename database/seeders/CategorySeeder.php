<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CategorySeeder extends Seeder
{
    public function run()
    {
        // Path to the JSON file
        $jsonFilePath = base_path('database/seeders/categories.json');

        // Check if the file exists
        if (!File::exists($jsonFilePath)) {
            throw new \Exception("The file at path {$jsonFilePath} does not exist.");
        }

        // Read JSON file
        $jsonContent = File::get($jsonFilePath);
        $categories = json_decode($jsonContent, true);

        // Check if categories are valid
        if (!is_array($categories)) {
            throw new \Exception('Invalid JSON data in categories file.');
        }

        // Clear the categories table
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('categories')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Insert categories and subcategories
        foreach ($categories as $category) {
            // Insert parent category

            $category_id = DB::table('categories')->insertGetId([
                'name' => json_encode($category['name'], JSON_UNESCAPED_UNICODE), // Ensure UTF-8 encoding
                'category_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Insert subcategories
            foreach ($category['subcategories'] as $subcategory) {
                if (empty($subcategory['name'])) {
                    continue; // Skip invalid subcategories
                }

                DB::table('categories')->insert([
                    'name' => json_encode($subcategory['name'], JSON_UNESCAPED_UNICODE), // Ensure UTF-8 encoding
                    'category_id' => $category_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
