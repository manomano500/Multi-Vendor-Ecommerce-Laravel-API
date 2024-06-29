<?php

use App\Http\Controllers\api\vendorr\OrderVendorController;
use App\Http\Controllers\api\vendorr\ProductVendorController;
use App\Http\Controllers\api\vendorr\StoreVendorController;
use App\Http\Controllers\StatisticsController;
use App\Models\Product;

Route::get('/statistics', [StatisticsController::class, 'vendorStatistic']);


Route::get('/stores', [StoreVendorController::class, 'show']);
Route::post('/stores', [StoreVendorController::class, 'update']);
Route::delete('/stores', [StoreVendorController::class, 'destroy']);


Route::get('/products', [ProductVendorController::class, 'index']);
Route::post('/products', [ProductVendorController::class, 'store']);
Route::get('/products/{id}', [ProductVendorController::class, 'show']);
Route::post('/products/{id}', [ProductVendorController::class, 'update']);
Route::delete('/products/{id}', [ProductVendorController::class, 'destroy']);




Route::get('/orders', [OrderVendorController::class, 'index']);
Route::get('/orders/{id}', [OrderVendorController::class, 'show']);





Route::get('/notifications', function () {
    return auth()->user()->notifications;
});




