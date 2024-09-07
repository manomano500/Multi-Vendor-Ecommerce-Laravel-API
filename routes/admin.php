<?php

use App\Http\Controllers\api\admin\CategoryAdminController;
use App\Http\Controllers\api\admin\OrderAdminController;
use App\Http\Controllers\api\admin\ProductAdminController;
use App\Http\Controllers\api\admin\StoreAdminController;
use App\Http\Controllers\api\admin\UserController;
use App\Http\Controllers\api\admin\VariationAdminController;
use App\Http\Controllers\api\NotificationController;
use App\Http\Controllers\api\public\CategoryController;
use App\Http\Controllers\api\StatisticsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::prefix('/categories')->group(function () {
    Route::get('/', [CategoryAdminController::class, 'index']);
    Route::post('/', [CategoryAdminController::class, 'store']);
    Route::get('/{category}', [CategoryAdminController::class, 'show']);
    Route::put('/{category}', [CategoryAdminController::class, 'update']);
    Route::delete('/{category}', [CategoryAdminController::class, 'destroy']);
});




Route::prefix('/variations')->group(function () {
    Route::get('/', [VariationAdminController::class, 'index']);
    Route::post('/', [VariationAdminController::class, 'store']);
    Route::put('/{id}', [VariationAdminController::class, 'update']);
    Route::delete('/{id}', [VariationAdminController::class, 'destroy']);
    Route::get('/{id}', [VariationAdminController::class, 'show']);
});

Route::post('/categories/{id}',[CategoryAdminController::class,'update']);
//TODO

Route::get('/statistics', [StatisticsController::class, 'adminStatistic']);




Route::get('/users',[UserController::class,'index']);
Route::get('/users/{id}',[UserController::class,'show']);
Route::post('/users',[UserController::class,'store']);
Route::delete('/users/{id}',[UserController::class,'destroy']);
Route::put('/users/{id}',[UserController::class,'update']);
//???
Route::get('/stores',[StoreAdminController::class,'index']);
Route::get('/stores/{id}',[StoreAdminController::class,'show']);
Route::post('/stores',[StoreAdminController::class,'store']);
Route::post('/stores/{id}',[StoreAdminController::class,'update']);
Route::delete('/stores/{id}',[StoreAdminController::class,'destroy']);




Route::get('/products',[ProductAdminController::class,'index']);
Route::get('/products/{id}',[ProductAdminController::class,'show']);
Route::post('/products/{id}',[ProductAdminController::class,'update']);
Route::delete('/products/{id}',[ProductAdminController::class,'destroy']);
Route::post('/products',[ProductAdminController::class,'store']);


Route::get('/orders',[OrderAdminController::class,'index']);
Route::get('/orders/{id}',[OrderAdminController::class,'show']);

//update the status of an order
Route::put('/orders/{id}/status',[OrderAdminController::class,'updateOrderStatus']);
Route::put('/orders/{order}/products/{product}/status', [OrderAdminController::class, 'updateOrderProductStatus']);


//Route::post('/orders/{orderId}/send-to-sabil', [OrderAdminController::class, 'sendOrderToSabil']);


Route::get('/notifications', [NotificationController::class, 'index']);
