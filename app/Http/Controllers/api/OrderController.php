<?php

namespace App\Http\Controllers\api;

use App\Events\OrderCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Providers\RouteServiceProvider;
use App\Services\PlutuService;
use Exception;
use Illuminate\Http\Request;

use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\ProductVariation;

use App\Services\OrderService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\View;

class OrderController extends Controller
{

    protected $orderService;
    protected $plutuService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
        $this->plutuService = new PlutuService();
    }
    public function index()
    {

        try {
            $orders = $this->orderService->getAllOrders(Auth::id());
            return OrderResource::collection($orders);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => 'Failed to retrieve orders'], 500);
        }
    }

    public function store(Request $request)
    {
       $orederRequest =OrderRequest::create($request);

       $validated =Validator::make($request->all(),$orederRequest->rules());

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()], 422);
        }


        try {
            Log::info('Creating order');
            Log::info($request->all());
            $order = $this->orderService->createOrder(Auth::id(), $request->all());

if($request->payment_method == 'Adfali') {
    $otpResponse = $this->plutuService->sendAdfaliOtp($request['mobile_number'], $order->id);

    if($otpResponse["status"]==="success"){


        return response()->json(['message' => 'OTP sent successfully',"processId"=>$otpResponse['processId'],"amount"=>$order->order_total], 200);

    }else
        return response()->json(['message' => 'Failed to send OTP'], 500);






}
if($request->payment_method == 'Sadad') {
    $sadadResponse = $this->plutuService->sendSadadOtp($request['mobile_number'], $order->id);

    if($sadadResponse["status"]==="success"){
        return response()->json(['message' => 'OTP sent successfully',"processId"=>$sadadResponse['processId'],"amount"=>$order->order_total], 200);
    }
}
if($request->payment_method == 'pay on deliver') {
    $order->payment_status = 'unpaid';
    return response()->json(['message' => 'Order created successfully'], 200);
}



            $order->save();



            return $order;
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }


    public function show($id)
    {
        try {
            $order = $this->orderService->getOrderById(Auth::id(), $id);
            return new OrderResource($order);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => 'Failed to retrieve the order'], 500);
        }
    }


       public function cancelOrder($id)
       {
              $order = Order::findOrFail($id);
              if ($order->user_id !== Auth::id()) {
                return response()->json(['message' => 'Unauthorized'], 401);
              }

              try {
                  $this->orderService->cancelOrder($order);
                  return new OrderResource($order);
              } catch (Exception $e) {
                  Log::error($e->getMessage());
                  return response()->json(['message' => $e->getMessage()], 500);
              }



       }
}
