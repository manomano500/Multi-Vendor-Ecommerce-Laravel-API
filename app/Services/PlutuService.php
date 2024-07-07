<?php
namespace App\Services;

use App\Models\Order;
use Exception;
use Illuminate\Support\Facades\Log;
use Plutu\Services\PlutuAdfali;

class PlutuService
{
    protected $adfali;

    public function __construct()
    {
        $this->adfali = new PlutuAdfali;

        $this->setCredentials($this->adfali);
    }

    protected function setCredentials($service)
    {
        $service->setCredentials(
            config('plutu.api_key'),
            config('plutu.access_token'),
            config('plutu.secret_key')
        );
    }

    // Adfali-specific methods
    public function sendAdfaliOtp($mobileNumber, $orderId)
    {
        try {
            // Fetch the order to get the amount
            $order = Order::findOrFail($orderId);
            Log::info('order: ' . $order);
            $amount = $order->order_total;
            Log::info($amount);// Assuming order_total is the correct amount field

            $response = $this->adfali->verify($mobileNumber, $amount);

            if ($response->getOriginalResponse()->isSuccessful()) {
                // Update the order status or save additional information if needed


                return [
                    'processId' => $response->getProcessId(),
                    'status' => 'success'
                ];
            } else {
                return [
                    'error' => $response->getOriginalResponse()->getErrorMessage(),
                    'status' => 'failed'
                ];
            }
        } catch (Exception $e) {
            return [
                'exception' => $e->getMessage(),
                'status' => 'failed'
            ];
        }
    }


    public function confirmAdfaliPayment($processId, $code, $amount, $invoiceNo)
    {
        try {
            $response = $this->adfali->confirm($processId, $code, $amount, $invoiceNo);

            if ($response->getOriginalResponse()->isSuccessful()) {

                return [
                    'transactionId' => $response->getTransactionId(),
                    'data' => $response->getOriginalResponse()->getBody(),
                    'status' => 'success'
                ];
            } else {
                return [
                    'error' => $response->getOriginalResponse()->getErrorMessage(),
                    'status' => 'failed'
                ];
            }
        } catch (Exception $e) {
            return [
                'exception' => $e->getMessage(),
                'status' => 'failed'
            ];
        }
    }

    // Fawry-specific methods

}
