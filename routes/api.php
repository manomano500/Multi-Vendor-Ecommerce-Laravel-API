<?php

use App\Http\Controllers\api\v1\CategoryController;
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








Route::get('/categories/parent', [CategoryController::class, 'getParentCategories']);


//Route::resource('/products', \App\Http\Controllers\api\v1\ProductController::class);
//Route::get('/products', [App\Http\Controllers\v1\ProductController::class, 'show']);
