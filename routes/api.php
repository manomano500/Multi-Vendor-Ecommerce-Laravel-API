<?php

use App\Http\Controllers\api\v1\public\CategoryController;
use App\Http\Controllers\api\v1\public\StoreController;
use App\Models\Order;



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










//get categories with their children
Route::get('/categories',[ CategoryController::class,'index']);
Route::get('/categories/with-children', [CategoryController::class, 'indexWhitChildren']);
//get the category with the prducts
Route::get('/categories/{id}', [CategoryController::class, 'show']);


Route::get('/variations', [\App\Http\Controllers\api\v1\VariationController::class, 'index']);

Route::get('/variations/{variationid}', [\App\Http\Controllers\api\v1\VariationController::class, 'show']);




Route::get('/products', [\App\Http\Controllers\api\v1\public\ProductController::class, 'index']);

Route::get('/stores',[StoreController::class,'index']);

Route::get('/test', function () {

    $order =Order::find(1);
    return response()->json(['$order'=>$order->products])    ;
});
