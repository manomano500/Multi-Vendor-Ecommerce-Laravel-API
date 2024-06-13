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
        // Get the logged-in user's store
        $store = Auth::user()->store;

        if (!$store) {
            return response()->json(['message' => 'No store found for this user'], 404);
        }

        // Get all store orders for the logged-in user's store with their products
        $storeOrders = StoreOrder::with([ 'orderProducts'=>function($query){
            $query->where('store_id', Auth::user()->store->id);

        }])
            ->where('store_id', $store->id)

            ->get();
        Log::info($storeOrders->where('store_id', $store->id));

        if ($storeOrders->isEmpty()) {
            return response()->json(['message' => 'No store orders found'], 404);
        }

        return response()->json(['data' => $storeOrders]);
    }


    public function getStoreOrdersWithProducts()
    {
        $storeId =Auth::user()->store;
        // Fetch all store orders for the given store ID with their products and pivot data
        $storeOrders = StoreOrder::all();

//        where('store_id', $storeId)->get();


        return response()->json(['data' => $storeOrders]);
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
