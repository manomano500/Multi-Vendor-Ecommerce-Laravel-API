<?php

use App\Http\Controllers\api\v1\ProductVendorController;
use App\Models\User;


Route::get('/stores', [\App\Http\Controllers\Auth\StoreController::class, 'show']);
Route::post('/stores', [\App\Http\Controllers\Auth\StoreController::class, 'update']);






Route::resource('/products', ProductVendorController::class);


Route::get('test', function () {

    $user =User::find(2);
//   Log::info($user->products);
    return response()->json(['$user'=>$user->products->groupBy('category_id')])    ;
});
