<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function index()
    {
        $orders= Auth::user()->orders;
        return OrderResource::collection($orders);
    }
    public function store(Request $request)
    {
        $orderRequest = OrderRequest::createFrom($request);
        $validated = Validator::make($orderRequest->all(), $orderRequest->rules());
        if ($validated->fails()) {
            return response()->json(['message' => $validated->errors()], 400);
        }
        $order = new Order($orderRequest->only(
            [
                'city',
                'shipping_address',

            ]));
        $order->user_id = Auth::id();
        $order->status = 'pending';
        $order->order_total = 0;
        $order->save();
        return response()->json(['message' => 'Order created successfully', 'order' => new OrderResource($order)], 201);

    }

    public function show($id)
    {
        $order = Order::findOrFail($id);
        return new OrderResource($order);
    }
}
