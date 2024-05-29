<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\AttributeValue;
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

        //        $this->call([
//            ProductSeeder::class,
//        ]);



        $this->call([
            CategorySeeder::class,
        ]);


         $this->call([
             AdminSeeder::class,
         ]);


        $this->call([
            UserSeeder::class]);


        $this->call([
            StoreSeeder::class]);



        $this->call([
            AttributeSeeder::class,
            ValueSeeder::class,
            AttributeValueSeeder::class,
        ]);



    }
}
