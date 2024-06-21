<?php

use App\Http\Controllers\api\admin\OrderAdminController;
use App\Http\Controllers\api\admin\StoreAdminController;

use App\Http\Controllers\api\public\CategoryController;
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

Route::post('/categories', [CategoryController::class, 'store']);
Route::get('/categories',[ CategoryController::class,'index']);
Route::post('/categories/{id}',[CategoryController::class,'update']);




//Route::get('/users',[UserController::class,'index']);



Route::get('/stores',[StoreAdminController::class,'index']);
Route::get('/stores/{id}',[StoreAdminController::class,'show']);

Route::get('/stores/{id}/products',[StoreAdminController::class,'showProducts']);

Route::get('/orders',[OrderAdminController::class,'index']);
Route::get('/orders/{id}',[OrderAdminController::class,'show']);
