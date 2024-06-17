<?php


use App\Http\Controllers\api\v1\OrderController;
use App\Http\Controllers\Auth\StoreController;
use App\Models\Order;


//Route::get('/stores',)

Route::post('/become-vendor', [StoreController::class, 'becomeVendor']);


Route::get('/test',function (){
   Order::create([
       'user_id'=>4,
       'status'=>'pending',
       'order_total'=>0,

   ]);
   return response()->json(['message'=>'order created']);
});




Route::get('/orders', [OrderController::class, 'index']);
Route::get('/orders/{id}', [OrderController::class, 'show']);
Route::post('/orders', [OrderController::class, 'store']);




