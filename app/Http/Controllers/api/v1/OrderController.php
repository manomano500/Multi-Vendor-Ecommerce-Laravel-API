<?php

namespace App\Http\Controllers\api\v1;

use App\Events\OrderCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderProductResource;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\ProductVariation;

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

   public function store(OrderRequest $request)
   {

       // Validate request
       $orderRequest = OrderRequest::createFrom($request);
       $validated = Validator::make($orderRequest->all(), $orderRequest->rules());
       if ($validated->fails()) {
           return response()->json(['message' => $validated->errors()], 400);
       }


       $productIds = collect($request['products'])->pluck('product_id');


       $productsInOrder = \App\Models\Product::whereIn('id', $productIds)->get();

       $order =Order::create(
           [
               'user_id'=>Auth::id(),
               'status'=>'pending',
               'order_total'=>0,
               'city'=>$request['city'],
               'phone'=>$request['phone'],
               'shipping_address'=>$request['shipping_address'],
           ]
       );
       try{
           DB::beginTransaction();

                $orderTotal = 0;
                foreach ($request['products'] as $productInserted) {
                    $product = $productsInOrder->find($productInserted['product_id']);
                    $orderProduct = new OrderProduct([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $productInserted['quantity'],
                        'price' => $product->price,
                        'store_id' => $product->store_id,
                    ]);
                    $orderTotal += $product->price * $productInserted['quantity'];
                    $orderProduct->save();
                }

                DB::commit();
                $order->update(['order_total' => $orderTotal]);
                $order->refresh();
                event(new OrderCreated($order));
                return new OrderResource($order);



       }catch (\Exception $e){
           Log::error($e->getMessage());
           return response()->json(['message'=>'An error occurred'],500);
       }

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
