<?php
namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Transaction;
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
//            'order_id' => 'required|exists:orders,id'
        ]);

        if($validated->fails()){
            Log::info($validated->errors());
            return response()->json($validated->errors(), 400);
        }

        $transaction = Transaction::where('process_id', $request->process_id)->first();

        $response = $this->plutuService->confirmAdfaliPayment($request->process_id, $request->code, $request->amount, $transaction->order_id);
if($response['status'] == 'success'){
    $transaction->update([
        'status' => 'success',
        'transaction_id' => $response['transactionId'], // 'transaction_id' => '1234567890
        'payment_response' => $response
    ]);
}else{

        $transaction->update([
            'status' => 'failed',
            'payment_response' => $response
        ]);



}
       Log::info( $response);
        return response()->json($response,200);
    }


    public function confirmSadadPayment(Request $request)
    {
        $validated =Validator::make($request->all(), [
            'process_id' => 'required|string',
            'code' => 'required|string',
            'amount' => 'required|numeric',
        ]);

        if($validated->fails()){
            Log::info($validated->errors());
            return response()->json($validated->errors(), 400);
        }
        $transaction = Transaction::where('process_id', $request->process_id)->first();

        $response = $this->plutuService->confirmSadadPayment($request->process_id, $request->code, $request->amount, $transaction->order_id);
        if($response['status'] == 'success'){
            $transaction->update([
                'status' => 'success',
                'transaction_id' => $response['transactionId'], // 'transaction_id' => '1234567890
                'payment_response' => $response
            ]);
            $transaction->order->update([
                'payment_status' => 'success'
            ]);
            $transaction->save();
        }else {

            $transaction->update([
                'status' => 'failed',
                'payment_response' => $response
            ]);
            $transaction->order->update([
                'payment_status' => 'failed'
            ]);
            $transaction->save();

        }


            Log::info( $response);
        return response()->json($response);

    }

    public function confirmLocalBankPayment(Request $request)
    {

        $order =Order::where('user_id',auth()->id())
            ->findOrFail($request->order_id);

        $response = $this->plutuService->localBankCards($order);
        Log::info( $response);
        return response()->json($response);

    }


    public function confirmMpgsPayment(Request $request)
    {
        $validated =Validator::make($request->all(), [
            'process_id' => 'required|string',
            'code' => 'required|string',
            'amount' => 'required|numeric',
        ]);

        if($validated->fails()){
            Log::info($validated->errors());
            return response()->json($validated->errors(), 400);
        }
        $transaction = Transaction::where('process_id', $request->process_id)->first();

        $response = $this->plutuService->confirmMpgsPayment($request->process_id, $request->code, $request->amount, $transaction->order_id);
        if($response['status'] == 'success'){
            $transaction->update([
                'status' => 'success',
                'transaction_id' => $response['transactionId'], // 'transaction_id' => '1234567890
                'payment_response' => $response
            ]);
            $transaction->order->update([
                'payment_status' => 'success'
            ]);
            $transaction->save();
        }else {

            $transaction->update([
                'status' => 'failed',
                'payment_response' => $response
            ]);
            $transaction->order->update([
                'payment_status' => 'failed'
            ]);
            $transaction->save();

        }

    }




}
