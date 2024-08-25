<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;


    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    /** @test */
    public function test_customer_can_complete_order_successfully()
    {
        $user = User::factory()->create(['role_id' => 3]);
        Auth::login($user);

        $product = Product::factory()->create(['status' => 'active']);
        $requestPayload = [
            'products' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 1,
                    'variations' => [
                        ['attribute' => 'color', 'value' => 'red']
                    ]
                ]
            ],
            'payment_method' => 'pay_on_deliver',
            'mobile_number' => '1234567890'
        ];

        $response = $this->postJson('/api/customer/orders', $requestPayload);

        $response->assertStatus(201);
    }


    public function test_customer_can_order_from_multiple_stores()
    {
        $user = User::factory()->create(["role_id" => 3]);
        $store1 = Store::factory()->create();
        $store2 = Store::factory()->create();
        $product1 = Product::factory()->create(['status' => 'active', 'store_id' => $store1->id]);
        $product2 = Product::factory()->create(['status' => 'active', 'store_id' => $store2->id]);

        $orderData = [
            'products' => [
                [
                    'product_id' => $product1->id,
                    'quantity' => 1,
                    'variations' => [
                        ['attribute' => 'color', 'value' => 'blue'],
                    ],
                ],
                [
                    'product_id' => $product2->id,
                    'quantity' => 1,
                    'variations' => [
                        ['attribute' => 'size', 'value' => 'medium'],
                    ],
                ],
            ],
            'payment_method' => 'pay_on_deliver',
        ];

        $response = $this->actingAs($user)
            ->postJson('/api/customer/orders', $orderData);

        $response->assertStatus(201);
    }

    public function test_customer_can_view_order_history()
    {
        $user = User::factory()->create(['role_id' => 3]);
        Auth::login($user);

        $order = Order::factory()->create(['user_id' => $user->id]);

        $response = $this->getJson('/api/customer/orders');

        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_customer_can_view_order_details()
    {
        $user = User::factory()->create(['role_id' => 3]);
        Auth::login($user);

        $order = Order::factory()->create(['user_id' => $user->id]);

        $response = $this->getJson("/api/customer/orders/{$order->id}");

        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_customer_can_cancel_order()
    {
        $user = User::factory()->create(['role_id' => 3]);
        Auth::login($user);

        $order = Order::factory()->create(['user_id' => $user->id]);

        $response = $this->deleteJson("/api/customer/orders/{$order->id}");

        $response->assertStatus(Response::HTTP_OK);
    }




}

