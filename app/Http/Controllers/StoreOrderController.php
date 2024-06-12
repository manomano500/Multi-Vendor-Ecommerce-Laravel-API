<?php


namespace App\Http\Controllers;

use App\Models\StoreOrder;
use Illuminate\Support\Facades\Auth;

class StoreOrderController extends Controller
{

    public function index()
    {

        $store = Auth::user()->store->id;
        return StoreOrder::where('store_id', $store->id);

    }

    public function approve(StoreOrder $storeOrder)
    {
        $storeOrder->update(['status' => 'approved']);
        $this->updateOrderStatus($storeOrder->order);
        return response()->json(['message' => 'Order approved successfully']);
    }

    public function deny(StoreOrder $storeOrder)
    {
        $storeOrder->update(['status' => 'denied']);
        $this->updateOrderStatus($storeOrder->order);
        return response()->json(['message' => 'Order denied successfully']);
    }

    protected function updateOrderStatus($order)
    {
        $storeOrders = $order->storeOrders;

        if ($storeOrders->every(fn($storeOrder) => $storeOrder->status === 'approved')) {
            $order->update(['status' => 'completed']);
        } elseif ($storeOrders->contains(fn($storeOrder) => $storeOrder->status === 'denied')) {
            $order->update(['status' => 'cancelled']);
        }
    }

}
