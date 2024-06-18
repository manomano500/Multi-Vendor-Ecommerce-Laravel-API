<?php


use App\Http\Controllers\api\v1\OrderController;
use App\Http\Controllers\Auth\StoreController;
use App\Models\Order;


//Route::get('/stores',)

Route::post('/become-vendor', [StoreController::class, 'becomeVendor']);






Route::get('/orders', [OrderController::class, 'index']);
Route::get('/orders/{id}', [OrderController::class, 'show']);
Route::post('/orders', [OrderController::class, 'store']);
Route::delete('/orders/{id}', [OrderController::class, 'cancelOrder']);




