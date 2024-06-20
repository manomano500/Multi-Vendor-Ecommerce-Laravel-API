<?php
namespace App\Services;

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
    public function sendAdfaliOtp($mobileNumber, $amount)
    {
        try {
            $response = $this->adfali->verify($mobileNumber, $amount);

            if ($response->getOriginalResponse()->isSuccessful()) {
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
        } catch (\Exception $e) {
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
        } catch (\Exception $e) {
            return [
                'exception' => $e->getMessage(),
                'status' => 'failed'
            ];
        }
    }

    // Fawry-specific methods

}
