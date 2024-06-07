<?php

use App\Http\Controllers\api\v1\public\CategoryController;
use App\Http\Controllers\api\v1\VendorApiController;
use App\Models\Product;
use App\Models\User;
use App\Models\Variation;


Route::get('/stores', [\App\Http\Controllers\StoreController::class, 'index']);


Route::get('/attributes', [\App\Http\Controllers\AttributeController::class, 'index']);




Route::resource('/products', VendorApiController::class);


Route::get('test', function () {

    $user =User::find(2);
//   Log::info($user->products);
    return response()->json(['$user'=>$user->products->groupBy('category_id')])    ;
});
