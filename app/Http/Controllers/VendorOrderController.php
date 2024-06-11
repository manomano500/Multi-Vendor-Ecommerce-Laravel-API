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
        $store = Auth::user()->store;
        $orders = $store->orders()->with('orderProducts.product')->get();

        return OrderResource::collection($orders->load('orderProducts.product'));

        // Return the orders as a collection of resources
    }






    public function approve(Request $request, Order $order)
    {
        // Check if the authenticated vendor is part of the order
        $vendor = auth()->user()->vendor;
        $orderProducts = $order->products()->whereHas('product', function ($query) use ($vendor) {
            $query->where('vendor_id', $vendor->id);
        })->get();

        foreach ($orderProducts as $orderProduct) {
            $orderProduct->update(['status' => 'approved']);
        }

        // Check if all order products are approved
        if ($order->products()->where('status', '!=', 'approved')->doesntExist()) {
            $order->update(['status' => 'approved']);
        }

        return response()->json(['message' => 'Order approved']);
    }

    public function deny(Request $request, Order $order)
    {
        // Check if the authenticated vendor is part of the order
        $vendor = auth()->user()->vendor;
        $orderProducts = $order->products()->whereHas('product', function ($query) use ($vendor) {
            $query->where('vendor_id', $vendor->id);
        })->get();

        foreach ($orderProducts as $orderProduct) {
            $orderProduct->update(['status' => 'denied']);
        }

        // Update the order status if any order product is denied
        $order->update(['status' => 'denied']);

        return response()->json(['message' => 'Order denied']);
    }
}
