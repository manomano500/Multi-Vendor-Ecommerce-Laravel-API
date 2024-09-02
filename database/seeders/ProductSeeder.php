<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Database\Factories\ProductImageFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ProductSeeder extends Seeder
{

        public function run()
    {
       Product::factory()
           ->create()
;    }

}
