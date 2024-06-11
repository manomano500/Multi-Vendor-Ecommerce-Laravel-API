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
use App\Notifications\OrderPlacedNotification;
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

    public function store(Request $request)
    {
        // Validate request
       $orderRequest = OrderRequest::createFrom($request);
        $validated = Validator::make($orderRequest->all(), $orderRequest->rules());
        if ($validated->fails()) {
            return response()->json(['message' => $validated->errors()], 400);
        }


        // Get the authenticated user


        // Calculate order total and add additional inputs
        $orderTotal = 0;



        $productsData = [];

        foreach ($request['products'] as $product) {

            $productModel = Product::findOrFail($product['product_id']);
            $productPrice = $productModel->price * $product['quantity'];
            $orderTotal += $productPrice;



            $productsData[] = [
                'product_id' => $product['product_id'],
                'quantity' => $product['quantity'],
                'price' => $productModel->price,
                'store_id' => $productModel->store_id, // Include store_id
            ];
            Log::info($productsData);

        }


        // Create order
        $order = new Order([
            'user_id' => Auth::id(),
            'order_total' => $orderTotal,
            'status' => 'pending', // Default status
            'city' => $request['city'],
            'shipping_address' => $request['shipping_address'],
        ]);

        try{
            DB::beginTransaction();
            $order->save();
            foreach ($productsData as $product) {
                OrderProduct::create([
                    'order_id' => $order->id,
                    'store_id' => $product['store_id'], // Include store_id
                    'product_id' => $product['product_id'],
                    'quantity' => $product['quantity'],
                    'price' => $product['price'],
                ]);

            }

            DB::commit();


            event(new OrderCreated($order));

            return OrderResource::make($order->load('orderProducts.product'));



        }catch (\Exception $e){
            DB::rollBack();
            return response()->json(['message' => 'Failed to create order','error'=>$e->getMessage()], 500);
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
