<?php

namespace App\Http\Controllers\api\vendorr;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Http\Resources\OrderVendorResource;
use App\Models\Order;
use App\Models\OrderProduct;
use Illuminate\Support\Facades\Auth;

class OrderVendorController extends Controller
{


    public function index()
    {
        $storeId =Auth::user()->store->id;

        $orderIds = OrderProduct::where('store_id', $storeId)
            ->pluck('order_id')
            ->unique();


        // Get the orders without the product details
        $orders = Order::whereIn('id', $orderIds)
            ->select(['id', 'user_id', 'status', 'payment_method', 'payment_status', 'shipping_address', 'created_at'])


            ->get()
        ;


        // Get orders with their products using the scope
//        $orders = Order::withStoreProducts($storeId)->get();

        // Structure the data
//return $orders;
        return  OrderVendorResource::collection($orders) ;
//



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
        return new OrderVendorResource($order);
    }







}
