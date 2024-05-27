<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{

        public function run()
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'a@a.a',
            'password' => Hash::make(12345678),
            'role_id' => 1,
        ]);
    }

}
