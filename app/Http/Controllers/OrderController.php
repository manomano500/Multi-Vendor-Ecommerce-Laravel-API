<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariation;
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

        $order = new Order($orderRequest->only(['city', 'shipping_address']));
        $order->user_id = Auth::id();
        $order->status = 'pending';
        $order->order_total = 0;

        try {
            foreach ($request->input('products') as $productData) {
                $product = Product::findOrFail($productData['product_id']);

                $selectedAttributes = [];

                foreach ($productData['variations'] as $variationData) {
                    $variation = ProductVariation::findOrFail($variationData);

                    if ($variation->product_id != $product->id) {
                        throw new \Exception('Invalid product variation.');
                    }

                    if (in_array($variation->attribute_id, $selectedAttributes)) {
                        throw new \Exception('Duplicate attribute selected for product.');
                    }

                    $selectedAttributes[] = $variation->attribute_id;

                    $order->products()->attach($product->id, [
                        'variation_id' => $variation->id,
                        'quantity' => $productData['quantity']
                    ]);

                    $order->order_total += $variation->price * $productData['quantity'];
                }
            }

            $order->save();
            return response()->json(['message' => 'Order created successfully', 'order' => new OrderResource($order)], 201);
        }catch (\Exception $e){
            return response()->json(['message' => $e->getMessage()], 400);
        }

    }

    public function show($id)
    {
        $order = Order::findOrFail($id);
        return new OrderResource($order);
    }
}
