<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use App\Models\Role;
use App\Models\Store;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

$this->call([
    CategorySeeder::class,

    EcommerceSeeder::class,
        ]);



        $this->call([
            AttributeSeeder::class,
            VariationSeeder::class,
//            CitySeeder::class,
        ]);


       /*  $this->call([
             AdminSeeder::class,
         ]);*/


        $this->call([
            UserSeeder::class,
            StoreSeeder::class,
//                    ProductSeeder::class
        ]);


        $this->call([
            OrderSeeder::class,
        ]);








    }
}
