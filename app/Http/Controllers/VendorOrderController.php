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

        // Get orders with their products using the scope
        $orders = Order::withStoreProducts($storeId)->get();

        // Structure the data

        return  VendorOrderResource::collection($orders) ;







        }



public function show($id)
    {
        $storeId =Auth::user()->store->id;

        // Get the order with the given ID
        $order = Order::withStoreProducts($storeId)->find($id);

        // If the order doesn't exist, return an error response
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Structure the data
        return new VendorOrderResource($order);
    }







}
