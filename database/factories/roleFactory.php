<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class roleFactory extends Factory
{
    protected $model = Role::class;

    public function definition()
    {
        return [
            ['name' => 'admin', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'customer', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'vendor', 'created_at' => now(), 'updated_at' => now()],
        ];
    }
}
