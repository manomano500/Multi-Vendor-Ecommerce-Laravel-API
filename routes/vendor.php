<?php

use App\Http\Controllers\api\vendorr\OrderVendorController;
use App\Http\Controllers\api\vendorr\ProductVendorController;
use App\Http\Controllers\api\vendorr\StoreVendorController;
use App\Models\Product;


Route::get('/stores', [StoreVendorController::class, 'show']);
Route::post('/stores', [StoreVendorController::class, 'update']);

Route::resource('/products', ProductVendorController::class);



Route::get('/orders', [OrderVendorController::class, 'index']);
Route::get('/orders/{id}', [OrderVendorController::class, 'show']);





Route::get('/notifications', function () {
    return auth()->user()->notifications;
});




Route::get('/test',function () {
        Product::factory()->count(100)->create([
            'store_id' => auth()->user()->store->id,
        ]);
    });
