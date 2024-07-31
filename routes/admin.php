<?php

use App\Http\Controllers\api\admin\OrderAdminController;
use App\Http\Controllers\api\admin\ProductAdminController;
use App\Http\Controllers\api\admin\StoreAdminController;
use App\Http\Controllers\api\admin\UserController;
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

Route::get('/categories',[ CategoryController::class,'index']);
Route::post('/categories', [CategoryController::class, 'store']);
Route::post('/categories/{id}',[CategoryController::class,'update']);
//TODO

Route::get('/statistics', [StatisticsController::class, 'adminStatistic']);


Route::delete('/users/{id}',[UserController::class,'destroy']);



Route::get('/admins',[UserController::class,'index']);
Route::post('/admins',[UserController::class,'store']);



Route::get('/stores',[StoreAdminController::class,'index']);
Route::get('/stores/{id}',[StoreAdminController::class,'show']);
Route::post('/stores/{id}',[StoreAdminController::class,'update']);
Route::delete('/stores/{id}',[StoreAdminController::class,'destroy']);
Route::get('/stores/{id}/products',[StoreAdminController::class,'showProducts']);




Route::get('/products',[ProductAdminController::class,'index']);
Route::delete('/products/{id}',[ProductAdminController::class,'destroy']);


Route::get('/orders',[OrderAdminController::class,'index']);
Route::get('/orders/{id}',[OrderAdminController::class,'show']);


//update the status of an order
Route::put('/orders/{id}/status',[OrderAdminController::class,'updateOrderStatus']);

//update the status of all products in an order
Route::put('/order-products/{orderProductId}/status', [OrderAdminController::class, 'updateOrderProductStatus']);




Route::get('/notifications', [NotificationController::class, 'index']);
