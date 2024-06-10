<?php

// database/seeders/CategorySeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run()
    {
        // Clear the categories table
        DB::table('categories')->delete();

        // Create categories with subcategories
        $categories = [
            [
                'name' => 'Electronics',
                'subcategories' => [
                    ['name' => 'Mobile Phones'],
                    ['name' => 'Laptops'],
                    ['name' => 'Cameras'],
                ],
            ],
            [
                'name' => 'Fashion',
                'subcategories' => [
                    ['name' => 'Men'],
                    ['name' => 'Women'],
                    ['name' => 'Kids'],
                ],
            ],
            [
                'name' => 'Home & Garden',
                'subcategories' => [
                    ['name' => 'Furniture'],
                    ['name' => 'Kitchen'],
                    ['name' => 'Decor'],
                ],
            ],
            [
                'name' => 'Health & Beauty',
                'subcategories' => [
                    ['name' => 'Makeup'],
                    ['name' => 'Skin Care'],
                    ['name' => 'Hair Care'],
                ],
            ],
            [
                'name' => 'Books',
                'subcategories' => [
                    ['name' => 'Fiction'],
                    ['name' => 'Non-Fiction'],
                    ['name' => 'Children'],
                ],
            ],
            [
                'name' => 'Sports',
                'subcategories' => [
                    ['name' => 'Fitness'],
                    ['name' => 'Running'],
                    ['name' => 'Cycling'],
                ],
            ],
            [
                'name' => 'Toys',
                'subcategories' => [
                    ['name' => 'Action Figures'],
                    ['name' => 'Dolls'],
                    ['name' => 'Educational'],

                ],
            ]
        ];





        // Insert categories and subcategories
        foreach ($categories as $category) {
            $category_id	 = DB::table('categories')->insertGetId([
                'name' => $category['name'],
                'category_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($category['subcategories'] as $subcategory) {
                DB::table('categories')->insert([
                    'name' => $subcategory['name'],
                    'category_id' => $category_id	,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
