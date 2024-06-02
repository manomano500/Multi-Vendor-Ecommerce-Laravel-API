<?php

use App\Http\Controllers\api\v1\public\CategoryController;
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










//get categories with their children
Route::get('/categories',[ CategoryController::class,'index']);
Route::get('/categories/parent', [CategoryController::class, 'getParentCategories']);



Route::get('/variations', [\App\Http\Controllers\api\v1\VariationController::class, 'index']);

Route::get('/variations/{variationid}', [\App\Http\Controllers\api\v1\VariationController::class, 'show']);




Route::get('/products', [\App\Http\Controllers\api\v1\public\ProductController::class, 'index']);
//Route::get('/variations/{attribute}', [\App\Http\Controllers\api\v1\VariationController::class, 'showWithAttribute']);
//Route::resource('/products', \App\Http\Controllers\api\v1\ProductController::class);
//Route::get('/products', [App\Http\Controllers\v1\ProductController::class, 'show']);
