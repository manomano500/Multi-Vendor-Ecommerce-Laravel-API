<?php
namespace App\Services;

use App\Models\Order;
use Exception;
use Illuminate\Support\Facades\Log;
use Plutu\Services\PlutuAdfali;
use Plutu\Services\PlutuLocalBankCards;
use Plutu\Services\PlutuSadad;

class PlutuService
{
    protected $adfali;
    protected $sadad;
    protected $localBankCards;

    public function __construct()
    {
        $this->adfali = new PlutuAdfali;
        $this->sadad = new PlutuSadad;
        $this->localBankCards = new PlutuLocalBankCards;


        $this->setCredentials($this->adfali);
        $this->setCredentials($this->sadad);
        $this->setCredentials($this->localBankCards);
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
    public function sendAdfaliOtp($mobileNumber, Order $order)
    {
        try {
            // Fetch the order to get the amount


            $amount = $order->order_total;

            $response = $this->adfali->verify($mobileNumber, $amount);

            if ($response->getOriginalResponse()->isSuccessful()) {

                $order->payment_status = 'otp_sent';
                $order->save();

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


    public function confirmAdfaliPayment($processId, $code, $amount, $orderId)
    {
        try {
//            orderId = invoice_no
            $response = $this->adfali->confirm($processId, $code, $amount, $orderId);

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


    public function sendSadadOtp($mobileNumber, $orderId)
    {
        try {
            // Fetch the order to get the amount
            $order = Order::findOrFail($orderId);
            $amount = $order->order_total;
            $birthYear = 1990; // Assuming the customer's birth year is 1990
            // Assuming order_total is the correct amount field

            $response = $this->sadad->verify($mobileNumber, $birthYear, $amount);

            if ($response->getOriginalResponse()->isSuccessful()) {
                // Update the order status or save additional information if needed
                $order->payment_status = 'otp_sent';
                $order->save();
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


    public function confirmSadadPayment($processId, $code, $amount, $invoiceNo)
    {
        try {
            $response = $this->sadad->confirm($processId, $code, $amount, $invoiceNo);

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


public function localBankCards(Order $order)
{
    try {
        $response = $this->localBankCards->confirm($order->order_total, $order->id, 'http://localhost:8000/logged-in', '', 'en');
        if ($response->getOriginalResponse()->isSuccessful()) {
            return [

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

}
