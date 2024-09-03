<?php

namespace App\Services;

use App\Events\OrderCreated;
use App\Helpers\ProductHelper;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderProduct;
use App\Models\Transaction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderService
{
    protected $plutuService;

    public function __construct(PlutuService $plutuService)
    {
        $this->plutuService = $plutuService;
    }

    public function getAllOrders($userId)
    {

        return Order::where('user_id', $userId)->get();
    }

    public function getOrderById($userId, $orderId)
    {
        $order = Order::where('id', $orderId)->where('user_id', $userId)->firstOrFail();
        $order->load(['products','user'])
        ;
        return $order;
    }

    public function createOrder($userId, $data)
    {
        try {

            DB::beginTransaction();
            // Validate that payment_method is provided
        Log::info($data);

/*            $order = Order::create([
                'user_id' => $userId,
                'status' => 'pending',
                'payment_method' => $data['payment_method'], // Make sure this is set
                'order_total' => 0,
            ]);*/

            $orderTotal = 0;

            // Get product IDs and quantities from the request data
            $productsData = $data['products'];

            // Fetch all active products in the order from the database
            $productIds = collect($productsData)->pluck('product_id');
            $products = Product::whereIn('id', $productIds)->where('status', 'active')->get();

            foreach ($productsData as $productData) {
                $product = $products->firstWhere('id', $productData['product_id']);
                $variations = $productData['variations'] ?? [];
                if (!is_array($variations)) {
                    $variations = json_decode($variations, true); // Ensure it's an array

                }
                $encodedVariations = json_encode($variations);
                $order = Order::create([
                    'user_id' => $userId,
                    'status' => 'pending',
                    'payment_method' => $data['payment_method'], // Make sure this is set
                    'order_total' => 0,
                ]);

                if ($product) {
                $orderProduct =    OrderProduct::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $productData['quantity'],
                        'price' => $product->price,
                        'variations' => $encodedVariations,
                    ]);
                    ProductHelper::deductProductQuantities($product, $productData['quantity']);
                    $orderTotal += $product->price * $productData['quantity'];
                }
            }

            $order->update(['order_total' => $orderTotal]);
            $order->refresh();


            DB::commit();
            event(new OrderCreated($order));
            return $order;

        } catch (Exception $e) {
            DB::rollBack();

            throw new Exception($e->getMessage());
        }


    }


    public function processPlutoOrderPayment(Order $order,Request $request )
   {
       if($request->payment_method == 'pay_on_deliver') {
           $order->payment_status = 'unpaid';
              $order->save();
           return Response()->json(['message' => 'Order created successfully'], 200);
       }

       if($request->payment_method == 'Adfali') {
           $otpResponse = $this->plutuService->sendAdfaliOtp($request['mobile_number'], $order);

           if($otpResponse["status"]==="success"){
               Transaction::create([
                   'order_id' => $order->id,
                   'payment_method' => 'Adfali', // 'Adfali' is added to the fillable array
                   'process_id' => $otpResponse['processId'],
                   'amount' => $order->order_total,
                   'status' => 'pending'
               ]);


               return $otpResponse;

           }else {

              throw new Exception('Failed to send OTP');
           }





       }
       if($request->payment_method == 'Sadad') {
           $otpResponse = $this->plutuService->sendSadadOtp($request['mobile_number'], $order->id);

           if($otpResponse["status"]==="success"){
               Transaction::create([
                   'order_id' => $order->id,
                   'payment_method' => 'Adfali', // 'Adfali' is added to the fillable array
                   'process_id' => $otpResponse['processId'],
                   'amount' => $order->order_total,
                   'status' => 'pending'
               ]);

               return $otpResponse;
           }else {

               throw new Exception($otpResponse);
           }
       }
       if($request->payment_method == 'localBankCards') {
           log::info('localBankCards');
           $localBankResponse = $this->plutuService->localBankCards($order);

        return $localBankResponse;
       }
       if($request->payment_method == 'mpgs'){
              $mpgsResponse = $this->plutuService->mpgs($order);

              return $mpgsResponse;
       }



   }
    public function deductProductQuantities(Product $product, $quantity): void
    {

        if ($product->quantity < $quantity) {
            throw new Exception('Insufficient quantity for product ' . $product->name);
        }

        $remainingQuantity = $product->quantity - $quantity;
        $product->update(['quantity' => $remainingQuantity]);
        if ($remainingQuantity < 2) {
            $product->update(['status' => 'out_of_stock']);
        }

        // Deduct quantities for each product in the order

    }

    public function cancelOrder(Order $order)
    {
        if ($order->status != 'pending' || $order->payment_status != 'pending') {
            throw new Exception('Order cannot be cancelled');
        }

        return DB::transaction(function () use ($order) {
            foreach ($order->products as $orderProduct) {
                $product = Product::find($orderProduct->pivot->product_id);
                if ($product) {
                    $product->quantity += $orderProduct->pivot->quantity;
                    $product->save();
                }
            }

            $order->status = 'cancelled';
            $order->save();

            return $order;
        });
    }
}
