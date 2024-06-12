<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductsListTest extends TestCase
{
//    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_products_list_returns_paginated_data_correctly(): void
    {
        Product::factory()->count(16)->create([
            'status' => 'active',
        ]);


        $response = $this->get('/api/products');

        $response->assertStatus(200);
        $response->assertJsonCount(16, 'data');
        $response->assertJsonPath('meta.last_page', 1);
    }
}
