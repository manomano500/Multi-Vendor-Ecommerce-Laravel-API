<?php

namespace App\Http\Controllers\services;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Plutu\Services\PlutuAdfali;

class PlutuController extends Controller
{
    protected $api;

    public function __construct()
    {
        $this->api = new PlutuAdfali;
        $this->api = new PlutuAdfali;
        $this->api->setCredentials(
            env('PLUTU_API_KEY'),
            env('PLUTU_ACCESS_TOKEN'),
            env('PLUTU_SECRET_KEY')   );
    }

    public function verify(Request $request)
    {
        $mobileNumber = $request->input('mobile_number');
        $amount = $request->input('amount');

        try {
            $apiResponse = $this->api->verify($mobileNumber, $amount);

            if ($apiResponse->getOriginalResponse()->isSuccessful()) {
                $processId = $apiResponse->getProcessId();
                return response()->json(['process_id' => $processId]);
            } else {
                $errorMessage = $apiResponse->getOriginalResponse()->getErrorMessage();
                return response()->json(['error' => $errorMessage], 400);
            }
        } catch (Exception $e) {
            return response()->json(['exception' => $e->getMessage()], 500);
        }
    }

    public function confirm(Request $request)
    {
        $processId = $request->input('process_id');
        $code = $request->input('code');
        $amount = $request->input('amount');
        $invoiceNo = $request->input('invoice_no');

        try {
            $apiResponse = $this->api->confirm($processId, $code, $amount, $invoiceNo);

            if ($apiResponse->getOriginalResponse()->isSuccessful()) {
                $transactionId = $apiResponse->getTransactionId();
                return response()->json(['transaction_id' => $transactionId]);
            } else {
                $errorMessage = $apiResponse->getOriginalResponse()->getErrorMessage();
                return response()->json(['error' => $errorMessage], 400);
            }
        } catch (Exception $e) {
            return response()->json(['exception' => $e->getMessage()], 500);
        }
    }
}
