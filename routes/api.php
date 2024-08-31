<?php

use App\Http\Controllers\api\public\CategoryController;
use App\Http\Controllers\api\public\ProductController;
use App\Http\Controllers\api\public\StoreController;
use App\Http\Controllers\api\public\VariationController;
use App\Http\Controllers\api\public\VariationsController;
use App\Http\Controllers\AttributeController;
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



//Route::resource('/products', ProductController::class);
Route::get('/products',[ProductController::class,'index']);
Route::get('/products/{id}',[ProductController::class,'show']);


Route::get('/stores',[StoreController::class,'index']);
Route::get('/stores/{id}/products', [StoreController::class, 'showProducts']);

Route::get('/stores/{storeId}/variations', [AttributeController::class, 'storeVariations']);
Route::get('/stores/{store_id}/filtered-products', [AttributeController::class, 'filteredProducts']);



Route::get('/payment-success', function () {
    return view('redirect.payment-success');
});

