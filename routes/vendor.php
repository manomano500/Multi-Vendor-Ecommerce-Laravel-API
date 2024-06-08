<?php

use App\Http\Controllers\api\v1\ProductController;
use App\Models\User;


Route::get('/stores', [\App\Http\Controllers\StoreController::class, 'index']);






Route::resource('/products', ProductController::class);


Route::get('test', function () {

    $user =User::find(2);
//   Log::info($user->products);
    return response()->json(['$user'=>$user->products->groupBy('category_id')])    ;
});
