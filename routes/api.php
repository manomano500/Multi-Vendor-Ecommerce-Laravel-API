<?php

use App\Http\Controllers\api\v1\public\CategoryController;
use App\Http\Controllers\api\v1\public\StoreController;
use App\Http\Controllers\api\v1\public\VariationController;
use App\Models\Order;
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
//get the category with the prducts
Route::get('/categories/{id}/products', [CategoryController::class, 'show']);


Route::get('/variations', [VariationController::class, 'index']);

//Route::get('/variations/{variationid}', [VariationController::class, 'show']);




Route::resource('/products', \App\Http\Controllers\api\v1\public\ProductController::class);

Route::get('/stores',[StoreController::class,'index']);

Route::get('/test', function () {

   $products = \App\Models\Product::find(1)->with('variations')->get();
      ;
});
