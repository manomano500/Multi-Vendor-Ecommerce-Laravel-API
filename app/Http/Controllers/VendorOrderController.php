<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Http\Resources\VendorOrderResource;
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
        $storeId =Auth::user()->store->id;
        $store = Store::findOrFail($storeId);

        // Get orders with their products using the scope
        $orders = Order::withStoreProducts($storeId)->get();

        // Structure the data

        return VendorOrderResource::collection($orders);







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
