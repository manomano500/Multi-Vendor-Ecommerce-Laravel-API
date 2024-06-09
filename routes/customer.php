<?php


use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\OrderController;
use App\Models\Order;


//Route::get('/stores',)

Route::post('/become-vendor', [\App\Http\Controllers\Auth\StoreController::class, 'becomeVendor']);


Route::get('/test',function (){
   Order::create([
       'user_id'=>4,
       'status'=>'pending',
       'order_total'=>0,

   ]);
   return response()->json(['message'=>'order created']);
});


Route::get('/orders', [OrderController::class, 'index']);
