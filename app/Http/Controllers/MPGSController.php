<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class MPGSController extends Controller
{
    public function confirmPayment(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'amount' => 'required|numeric',
            'invoice_no' => 'required|string',
            'return_url' => 'required|url',
        ]);

        // Set up GuzzleHTTP client
        $client = new Client();

        // Make the API request to Plutu
        try {
            $response = $client->post('https://api.plutus.ly/api/v1/transaction/mpgs/confirm', [
                'headers' => [
                    'X-API-KEY' => env('PLUTU_API_KEY'),
                    'Authorization' => 'Bearer ' . env('PLUTU_ACCESS_TOKEN'),
                ],
                'form_params' => [
                    'amount' => $request->amount,
                    'invoice_no' => $request->invoice_no,
                    'return_url' => $request->return_url,
                    'customer_ip' => $request->ip(),
                ],
            ]);

            $data = $response->getBody()->getContents();
            $responseData = json_decode($data, true);

            return response()->json($responseData, $response->getStatusCode());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
