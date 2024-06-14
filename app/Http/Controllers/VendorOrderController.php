<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Models\Store;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderProduct;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class VendorOrderController extends Controller
{



        public function index()
    {
        // Get the logged-in user's store
        $storeId = Auth::user()->store->id;
        Log::info($storeId);


        $store = Store::findOrFail($storeId);

        // Get orders with their products
        $orders = Order::whereHas('products', function ($query) use ($storeId) {
            $query->where('order_products.store_id', $storeId); // specify the table alias
        })->with(['products' => function ($query) use ($storeId) {
            $query->where('order_products.store_id', $storeId); // specify the table alias
        }])->get();




        return  OrderResource::collection($orders);


        }









    public function approve(Request $request, $orderId)
    {
        $storeOrder = StoreOrder::find($orderId);
        Log::info($storeOrder);
        if(Auth::user()->store->id != $storeOrder->store_id){
            return response()->json(['message' => 'You are not authorized to update this order'], 403);
        }

        Log::info('order: ' . $storeOrder);
       $storeOrder->update(['status' => 'accepted']);
        return response()->json(['message' => 'Order updated successfully']);
    }

    public function reject(Request $request,  $orderId)
    {

        $storeOrder = StoreOrder::find($orderId);
        Log::info($storeOrder);
        if(Auth::user()->store->id != $storeOrder->store_id){
            return response()->json(['message' => 'You are not authorized to update this order'], 403);
        }

        Log::info('order: ' . $storeOrder);
        $storeOrder->update(['status' => 'rejected']);
        return response()->json(['message' => 'Order updated successfully']);
    }
}
