<?php

use App\Http\Controllers\api\v1\CategoryController;
use App\Http\Controllers\api\v1\StoreController;
use App\Http\Controllers\api\v1\VariationController;
use Illuminate\Http\Request;


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
        return response()->json($request->user());    });


});










//get categories with their children
Route::get('/categories',[ CategoryController::class,'index']);
Route::get('/categories/with-children', [CategoryController::class, 'indexWhitChildren']);
Route::get('/categories/{id}/stores', [CategoryController::class, 'show']);



Route::get('/variations', [VariationController::class, 'index']);



Route::resource('/products', \App\Http\Controllers\api\v1\ProductController::class);


Route::get('/stores',[StoreController::class,'index']);
Route::get('/stores/{id}/products', [StoreController::class, 'showProducts']);


