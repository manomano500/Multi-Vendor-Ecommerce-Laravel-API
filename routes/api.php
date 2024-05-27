<?php

use Illuminate\Http\Request;
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

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });





});





Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::apiResource('/users', \App\Http\Controllers\api\admin\UserController::class);
//    Route::apiResource('stores', AdminStoreController::class);
    Route::apiResource('stores', \App\Http\Controllers\api\admin\stores\StoreController::class);

//    Route::apiResource('products', AdminProductController::class);
    // Other admin routes
});


Route::get('/categories', 'App\Http\Controllers\api\admin\CategoryController@index');
