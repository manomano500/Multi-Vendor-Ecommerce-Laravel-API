<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Jane Doe',
            'email' => 'a@a.a',
            'password' => bcrypt('12345678'),
            'role_id' => 1,
            'phone' => '08012345678',
            'address' => 'tarik alsour',

        ]);
        DB::table('users')->insert([
            'name' => 'John Doe',
            'email' => 'v@v.v',
            'password' => bcrypt('12345678'),
            'role_id' => 2,
            'phone' => '08012345678',
            'address' => 'tarik alsour',

        ]);
        Db::table('stores')->insert([
            'name' => 'John Doe Store',
            'description' => 'This is John Doe Store',
            'category_id' => 1,
            'user_id' => User::where('email', 'v@v.v')->first()->id,
            'image' => 'https://via.placeholder.com/150',
            'status' => 'active',
            'address' => '123 Main St, Lagos',
            'phone' => '08012345678',
            'email' => 'v@v.v',
        ]);
        DB::table('users')->insert([
            'name' => 'Jane Doe',
            'email' => 'c@c.c',
            'password' => bcrypt('12345678'),
            'role_id' => 3,
            'phone' => '08012345678',
            'address' => 'tarik alsour',
        ]);

        User::factory(10)->create();



    }
}
