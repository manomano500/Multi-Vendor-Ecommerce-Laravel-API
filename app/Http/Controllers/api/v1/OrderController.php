<?php

namespace App\Http\Controllers\api\v1;

use App\Events\OrderCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderProductResource;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\OrderProductVariation;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\StoreOrder;
use App\Notifications\NewStoreOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Auth::user()->orders;
        return OrderResource::collection($orders);
    }

   public function store(OrderRequest $request){

       // Validate request
       $orderRequest = OrderRequest::createFrom($request);
       $validated = Validator::make($orderRequest->all(), $orderRequest->rules());
       if ($validated->fails()) {
           return response()->json(['message' => $validated->errors()], 400);
       }

         // Create order

        $order = Order::create([
            'user_id' => Auth::id(),
            'status' => 'pending',
            'order_total' => 0,
            'city' => $orderRequest->city,
            'shipping_address' => $orderRequest->shipping_address,

        ]);

       $order->save();
       $orderTotal = 0;

       foreach ($orderRequest->products as $product) {
           $orderProducts = OrderProduct::create([
               'order_id' => $order->id,
               'product_id' => $product['product_id'],
               'quantity' => $product['quantity'],
               'price' => Product::find($product['product_id'])->price,
               $orderTotal += Product::find($product['product_id'])->price * $product['quantity'],
           ]);
       }
       $order->order_total = $orderTotal;
   }


        function show($id)
        {

            $order = Order::findOrFail($id)->load('products');
            if ($order->user_id !== Auth::id()) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            return new OrderResource($order);
        }

}
