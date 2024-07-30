<?php
namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Services\PlutuService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Log;

class PaymentController extends Controller
{
    protected $plutuService;


    public function __construct(PlutuService $plutuService)
    {
        $this->plutuService = $plutuService;
    }




    public function confirmAdfaliPayment(Request $request)
    {
        Log::info($request->all());
        Log::info("confirmAdfaliPayment");
        $validated =Validator::make($request->all(), [
            'process_id' => 'required|string',
            'code' => 'required|string',
            'amount' => 'required|numeric',
//            'invoice_no' => 'required|string'
        ]);

        if($validated->fails()){
            Log::info($validated->errors());
            return response()->json($validated->errors(), 400);
        }
        $invoice_no = "127694";

        $response = $this->plutuService->confirmAdfaliPayment($request->process_id, $request->code, $request->amount, $invoice_no);
if($response['status'] == 'success'){

}
       Log::info( $response);
        return response()->json($response);
    }


    public function confirmSadadPayment(Request $request)
    {
        $validated =Validator::make($request->all(), [
            'process_id' => 'required|string',
            'code' => 'required|string',
            'amount' => 'required|numeric',
//            'invoice_no' => 'required|string'
        ]);

        $invoice_no = "127694";
        if($validated->fails()){
            Log::info($validated->errors());
            return response()->json($validated->errors(), 400);
        }

        $response = $this->plutuService->confirmSadadPayment($request->process_id, $request->code, $request->amount, $invoice_no);

        Log::info( $response);
        return response()->json($response);

    }

    public function confirmLocalBankPayment()
    {
        $response = $this->plutuService->localBankCards();
        Log::info( $response);
        return response()->json($response);

    }

}
