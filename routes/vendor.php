<?php

use App\Http\Controllers\api\v1\OrderController;
use App\Http\Controllers\api\v1\ProductVendorController;
use App\Http\Controllers\OrderVendorController;
use App\Models\Product;
use App\Models\User;


Route::get('/stores', [\App\Http\Controllers\Auth\StoreController::class, 'show']);
Route::post('/stores', [\App\Http\Controllers\Auth\StoreController::class, 'update']);

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
