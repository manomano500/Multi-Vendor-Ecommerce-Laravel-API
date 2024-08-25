<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class PaymentControllerTest
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_an_order_successfully_with_adfali_payment_method_and_updates_status()
    {
        // Create a user and log them in
        $user = User::factory()->create(['role_id' => 3]);
        Auth::login($user);

        // Create a product and a store
        $product = Product::factory()->create(['status' => 'active', 'price' => 100, 'quantity' => 10]);
        $store = $product->store; // Assuming you have a relationship between products and stores

        // Create a request payload with Adfali payment method
        $orderPayload = [
            'products' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                    'variations' => [
                        ['attribute' => 'color', 'value' => 'red']
                    ]
                ]
            ],
            'payment_method' => 'Adfali',
            'mobile_number' => '0913632323'
        ];

        // Create an order
        $orderResponse = $this->postJson('/api/customer/orders', $orderPayload);
        $orderResponse->assertStatus(201);

        // Extract the processId from the response
        $processId = $orderResponse->json('processId');
        $order = Order::where('user_id', $user->id)->first();

        // Simulate the Adfali payment confirmation
        Log::info($processId);
        $paymentPayload = [
            'process_id' => $processId,
            'code' => '1111',
            'amount' => $order->order_total // Ensure this matches the actual order total
        ];

        $paymentResponse = $this->postJson('/api/customer/payment/adfali/confirm', $paymentPayload);
        $paymentResponse->assertStatus(200);

        // Check if the order's payment status is updated
        $order->refresh();
        $this->assertEquals('paid', $order->payment_status);

        // Verify the transaction was created
        $transaction = Transaction::where('order_id', $order->id)->first();
        $this->assertNotNull($transaction);
        $this->assertEquals('Adfali', $transaction->payment_method);
        $this->assertEquals('success', $transaction->status);
    }

    /** @test */
    public function it_fails_to_create_an_order_with_invalid_adfali_payment_method()
    {
        // Create a user and log them in
        $user = User::factory()->create(['role_id' => 3]);
        Auth::login($user);

        // Create a product
        $product = Product::factory()->create(['status' => 'active', 'quantity' => 10]);

        // Create a request payload with missing mobile number
        $orderPayload = [
            'products' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                    'variations' => [
                        ['attribute' => 'color', 'value' => 'red']
                    ]
                ]
            ],
            'payment_method' => 'Adfali',
            // 'mobile_number' is missing
        ];

        // Create an order
        $orderResponse = $this->postJson('/api/customer/orders', $orderPayload);
        $orderResponse->assertStatus(422)
            ->assertJsonValidationErrors(['mobile_number']);
    }
}
