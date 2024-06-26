<?php

namespace App\Http\Controllers\api\admin;

use App\Events\OrderProductUpdated;
use App\Http\Controllers\Controller;
use App\Http\Resources\admin\OrderResource;
use App\Models\Order;
use App\Models\OrderProduct;
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
        return OrderResource::make($order);
    }


    public function updateOrderProductStatus(Request $request, $order, $store)
    {

        $validated = Validator::make($request->all(), [
            'status' => 'required|in:dropped_off,cancelled,delivered,returned',
//            'store_id' => 'required|exists:order_product,store_id',
        ]);

        if ($validated->fails()) {
            return response()->json(['message' => $validated->errors()->first()], 422);
        }

        try {
            DB::beginTransaction();


            $newStatus = $request->input('status');
Log::info($newStatus);
            $orderProducts = OrderProduct::where('order_id', $order)
                ->where('store_id', $store)
                ->get();
//Log::info($orderProducts);
            foreach ($orderProducts as $orderProduct) {
                Log::info($orderProduct);
                $orderProduct->status = $newStatus;
                $orderProduct->save();
                Log::info($orderProduct);


//                $orderProduct->update(['status' => $newStatus]);
                event(new OrderProductUpdated($orderProduct));
            }

            DB::commit();

            return response()->json(['message' => 'All products updated successfully', 'data' => $orderProducts]);
        } catch (\Exception $exception) {
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

        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            return response()->json(['message' => 'something went wrong','error'=>$exception->getMessage()], 500);
        }
    }

}
