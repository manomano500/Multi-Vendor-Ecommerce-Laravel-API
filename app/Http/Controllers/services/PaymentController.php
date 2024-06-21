<?php
namespace App\Http\Controllers\services;

use App\Http\Controllers\Controller;
use App\Services\PlutuService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    protected $plutuService;

    public function __construct(PlutuService $plutuService)
    {
        $this->plutuService = $plutuService;
    }

    // Adfali payment methods
    public function sendAdfaliOtp(Request $request)
    {
        $request->validate([
            'mobile_number' => 'required|string',
            'amount' => 'required|numeric'
        ]);

        $response = $this->plutuService->sendAdfaliOtp($request->mobile_number, $request->amount);

        return response()->json($response);
    }

    public function confirmAdfaliPayment(Request $request)
    {
        $validated =Validator::make($request->all(), [
            'process_id' => 'required|string',
            'code' => 'required|string',
            'amount' => 'required|numeric',
            'invoice_no' => 'required|string'
        ]);
       if($validated->fails()){
           return response()->json($validated->errors(), 400);
         }

        $response = $this->plutuService->confirmAdfaliPayment($request->process_id, $request->code, $request->amount, $request->invoice_no);

        return response()->json($response);
    }

}
