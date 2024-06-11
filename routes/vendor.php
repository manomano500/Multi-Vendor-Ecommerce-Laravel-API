<?php

use App\Http\Controllers\api\v1\OrderController;
use App\Http\Controllers\api\v1\ProductVendorController;
use App\Http\Controllers\VendorOrderController;
use App\Models\User;


Route::get('/stores', [\App\Http\Controllers\Auth\StoreController::class, 'show']);
Route::post('/stores', [\App\Http\Controllers\Auth\StoreController::class, 'update']);






Route::resource('/products', ProductVendorController::class);



Route::get('/orders', [VendorOrderController::class, 'index']);
    Route::post('vendor/orders/{order}/approve', [OrderController::class, 'approve']);
    Route::post('vendor/orders/{order}/deny', [OrderController::class, 'deny']);
