<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderProduct;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class OrderAdminController extends Controller
{
    public function index()
    {

        $orders = Order::latest()->get();

        return  $orders;
    }


    public function show($id)
    {
        $order = Order::with('orderProducts', 'user')->findOrFail($id);
        return $order;
//        return OrderResource::make($order);
    }


    public function updateOrderProductStatus(Request $request, $orderProductId)
    {

        $orderProduct = OrderProduct::findOrFail($orderProductId);

//        $validated = Validator::make($request->all(), [
//            'status' => 'required|in:dropped_off,cancelled,delivered,returned',
////            'store_id' => 'required|exists:order_product,store_id',
//        ]);
//
//        if ($validated->fails()) {
//            return response()->json(['message' => $validated->errors()->first()], 422);
//        }

        try {
            DB::beginTransaction();



 $orderProduct= OrderProduct::where('id',$orderProductId)->first();

// Log::error($orderProduct->quantity*$orderProduct->price);
 $productSubPrice=$orderProduct->order->order_total- $orderProduct->quantity*$orderProduct->price;
 Log::error($productSubPrice);
$orderProduct->order()->update(['order_total'=>$productSubPrice]);
    $orderProduct->delete();
            DB::commit();

            return response()->json(['message' => 'All products updated successfully', 'data' => "orderProduct"]);
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());

            return response()->json(['message' => 'Something went wrong', 'error' => $exception->getMessage()], 500);
        }
    }


    public function updateOrderStatus(Request $request, $id)
    {
        $validated = Validator::make(request()->all(), [
            'status' => 'required|in:shipped,delivered,cancelled',
        ]);
        if ($validated->fails()) {
            return response()->json(['message' => $validated->errors()->first()], 422);
        }
        try {
            DB::beginTransaction();
            $order = Order::findOrFail($id);
            $order->status = $request->input('status');
            $order->save();
//            event(new OrderProductUpdated($order));
            DB::commit();
            return response()->json(['message' => 'updated successfully']);

        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            return response()->json(['message' => 'something went wrong','error'=>$exception->getMessage()], 500);
        }
    }

}
