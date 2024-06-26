<?php


use App\Http\Controllers\api\OrderController;
use App\Http\Controllers\Auth\BecomeVendor;
use App\Http\Controllers\services\PaymentController;


//Route::get('/stores',)

Route::post('/become-vendor', [BecomeVendor::class, 'becomeVendor']);





Route::get('/orders', [OrderController::class, 'index']);
Route::get('/orders/{id}', [OrderController::class, 'show']);
Route::post('/orders', [OrderController::class, 'store']);
Route::delete('/orders/{id}', [OrderController::class, 'cancelOrder']);


Route::post('/payment/adfali/send-otp', [PaymentController::class, 'sendAdfaliOtp']);
Route::post('/payment/adfali/confirm', [PaymentController::class, 'confirmAdfaliPayment']);


