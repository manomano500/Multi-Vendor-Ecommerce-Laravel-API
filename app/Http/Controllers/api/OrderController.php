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
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
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
        $orederRequest = OrderRequest::create($request);

        $validated = Validator::make($request->all(), $orederRequest->rules());

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()], 422);
        }


        try {

            $order = $this->orderService->createOrder(Auth::id(), $request->all());

            $processingResponse = $this->orderService->processPlutoOrderPayment($order, $request);

            Log::info($processingResponse);
            return $processingResponse;

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
