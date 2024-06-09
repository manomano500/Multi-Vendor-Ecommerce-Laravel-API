<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;

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
       $validated =Validator::make($orderRequest->all(), $orderRequest->rules());
         if ($validated->fails()) {
                  return response()->json(['message' => $validated->errors()], 400);
    }

         $order =new Order([
                'user_id'=>Auth::id(),
                'status'=>'pending',
                'order_total'=>0,
            ]);
         foreach ($request->input('products') as $product) {
             $productItem = Product::where('id',$product['product_id'])->first()
             ->load('variations');
             $order->order_total += $productItem->price * $product['quantity'];

            foreach($product['variations'] as $variation){



                Log::info($variation);
            }
         }





    return Response()->json(['message'=>'Order created successfully'],201);
    }

    public function show($id)
    {
        $order = Order::findOrFail($id)->load('products');
        return new OrderResource($order);
    }
}
