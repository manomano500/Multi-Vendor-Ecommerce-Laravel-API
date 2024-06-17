<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\StripeService;

class StripeController extends Controller
{
    protected $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    public function createPaymentIntent(Request $request)
    {
        $amount = $request->input('amount');

        try {
            $paymentIntent = $this->stripeService->createPaymentIntent($amount);

            return response()->json([
                'client_secret' => $paymentIntent->client_secret,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
