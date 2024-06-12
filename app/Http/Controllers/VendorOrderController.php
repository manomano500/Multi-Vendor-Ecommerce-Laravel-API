<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Models\Store;
use App\Models\StoreOrder;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderProduct;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class VendorOrderController extends Controller
{


    public function index()
    {
        $store = Auth::user()->store;
        $orders = StoreOrder::all()->where('store_id', $store->id);
return $orders;
//        return OrderResource::collection($orders->load('orderProducts.product'));

        // Return the orders as a collection of resources
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
