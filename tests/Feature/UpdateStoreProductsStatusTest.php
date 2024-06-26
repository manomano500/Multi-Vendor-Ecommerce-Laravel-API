<?php

namespace Tests\Feature;

use App\Models\User; // Replace with your admin user model
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdateStoreProductsStatusTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_update_all_products_status_for_a_store_in_an_order()
    {
        // Create an admin user (replace with your admin creation logic)
        $admin = User::factory()->create([
            'role_id' => 1,
        ]);

        // Simulate authentication as the admin user
        Sanctum::actingAs($admin, ['admin']);


        // Create a category


        // Create an active store with a valid category_id
        $store = Store::factory()->create([
'category_id' => Category::factory()->create([
    'category_id' => null]
)->id, // Add this line
            'status' => 'active',
        ]);

        // Create active products for the active store
        $products = Product::factory()->count(5)->create([
            'store_id' => $store->id,
            'status' => 'active',
        ]);

        // Create an order with products from the store
        $order = Order::factory()->create();

        // Associate products with the order
        foreach ($products as $product) {
            OrderProduct::factory()->create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'store_id' => $store->id,
            ]);
        }

        // Prepare the update payload
        $payload = [
            'status' => 'delivered',
            'store_id' => $store->id,
        ];

        // Send the request to update the status of all products for the store
        $response = $this->json('PUT', "/api/admin/orders/{$order->id}/order-products/status", $payload);

        // Assert the response status
        $response->assertStatus(200);

        // Assert the status of products have been updated
        foreach ($products as $product) {
            $this->assertDatabaseHas('order_product', [
                'product_id' => $product->id,
                'status' => 'delivered',
            ]);
        }
    }
}
