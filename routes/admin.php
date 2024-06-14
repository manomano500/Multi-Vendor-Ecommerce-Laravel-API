<?php

use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\AdminStoreController;
use App\Http\Controllers\api\v1\CategoryController;
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

Route::post('/add-category', [CategoryController::class, 'addParentCategory']);






Route::get('/stores',[AdminStoreController::class,'index']);


Route::get('/orders',[AdminOrderController::class,'index']);
