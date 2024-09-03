<?php

namespace App\Services;

use App\Models\Order;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class SabilService
{
    protected $client;
    protected $baseUrl;
    protected $token;

    public function __construct()
    {
        $this->client = new Client();
        $this->baseUrl = config('services.sabil.base_url');
        $this->token = config('services.sabil.token');
    }

    public function sendOrder($orderId)
    {
        $order = $this->getOrderDetails($orderId);

        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json', // Ensure you get JSON in response

        ];

        $options = [
            'form_params' => $this->prepareOrderData($order),
        ];

        Log::info('Sending order with options: ', $options);

        try {
            $response = $this->client->post("https://api.sabil.ly/v1/orders/jouba/", [
                'headers' => $headers,
                'form_params' => $options['form_params'],
            ]);

            Log::info('Response received: ' . $response->getBody());
            $responseBody = $response->getBody();
            $jsonResponse = json_decode($responseBody, true);

            return response()->json($jsonResponse); // Return the
        } catch (\Exception $e) {
            Log::error('Failed to send order to Sabil.ly: ' . $e->getMessage());
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }

    private function getOrderDetails($orderId)
    {
        // Fetch the order details from your application
        return Order::with('orderProducts', 'user', 'orderProducts.product')->findOrFail($orderId);
    }

    private function prepareOrderData($order)
    {
        $products = $order->orderProducts->map(function ($orderProduct) {
            return [
                'sku' => "Z33",
                'title' => 'product title',
                'amount' => 44,
                'quantity' => 4,
                'isRefundable' => 'true', // Assuming this should be a string
                'size' => [
                    'scale' => 'Centimeter',
                    'width' => 5,
                    'height' => 3,
                    'length' => 4,
                ],
                'weight' => [
                    'scale' => 'Kg',
                    'value' => 5
                ],
            ];
        })->toArray();

        return [
            'servicePackageId' => 'tosyl-rgaly',
            'title' => 'A Gift to someone!',
            'pickFromDoor' => 'true',
            'dropToDoor' => 'false',
            'destination[from][city]' => 'طرابلس 1',
            'destination[from][address]' => 'ABC street 1234',
            'destination[to][city]' => 'السواني 20',
            'destination[to][address]' => 'ABC street 1234',
            'products' => $products,
            'receivers[0][fullName]' => 'John Doe',
            'receivers[0][contact]' => '03056762168',
            'paymentBy' => 'Receiver',
            'productPayment' => 'Included',
            'paymentMethod' => 'Cash',
            'allowBankPayment' => 'false',
            'notes' => 'Some message here...',
            'labels[0]' => 'label1',
            'labels[1]' => 'label2',
        ];
    }
}
