<?php


use App\Http\Controllers\api\NotificationController;
use App\Http\Controllers\api\OrderController;
use App\Http\Controllers\api\PaymentController;
use App\Http\Controllers\Auth\BecomeVendor;


Route::post('/become-vendor', [BecomeVendor::class, 'becomeVendor']);





Route::get('/orders', [OrderController::class, 'index']);
Route::get('/orders/{id}', [OrderController::class, 'show']);
Route::post('/orders', [OrderController::class, 'store']);
Route::delete('/orders/{id}', [OrderController::class, 'cancelOrder']);


Route::get('/notifications', [NotificationController::class, 'index']);


Route::post('/payment/adfali/confirm', [PaymentController::class, 'confirmAdfaliPayment']);
Route::get('/payment/adfali/send-otp', [PaymentController::class, 'sendOtp']);
Route::post('/payment/sadad/confirm', [PaymentController::class, 'confirmSadadPayment']);
Route::post('/payment/localbanks/confirm', [PaymentController::class, 'confirmLocalBankPayment']);
Route::post('/payment/credit-card/confirm', [PaymentController::class, 'confirmCreditCardPayment']);


