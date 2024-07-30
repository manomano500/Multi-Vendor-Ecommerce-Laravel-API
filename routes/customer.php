<?php


use App\Http\Controllers\api\OrderController;
use App\Http\Controllers\api\PaymentController;
use App\Http\Controllers\Auth\BecomeVendor;


Route::post('/become-vendor', [BecomeVendor::class, 'becomeVendor']);





Route::get('/orders', [OrderController::class, 'index']);
Route::get('/orders/{id}', [OrderController::class, 'show']);
Route::post('/orders', [OrderController::class, 'store']);
Route::delete('/orders/{id}', [OrderController::class, 'cancelOrder']);


Route::post('/payment/adfali/confirm', [PaymentController::class, 'confirmAdfaliPayment']);
//Route::post('/payment/adfali/confirm', [PaymentController::class, 'confirmAdfaliPayment']);
Route::post('/payment/sadad/confirm', [PaymentController::class, 'confirmSadadPayment']);


Route::post('/payment/localbanks/confirm', [PaymentController::class, 'confirmLocalBankPayment']);
