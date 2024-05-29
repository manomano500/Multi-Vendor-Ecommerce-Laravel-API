<?php

use App\Http\Controllers\AttributeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\v1\CategoryController;
use App\Http\Controllers\v1\VendorApiController;
use App\Models\Attribute;



Route::get('/categories/parent', [CategoryController::class, 'getParentCategories']);
Route::get('/categories/{category}/children', [CategoryController::class, 'getChildrenCategories']);



Route::get('/attributes', [\App\Http\Controllers\AttributeController::class, 'index']);




Route::get('/products', [ProductController::class, 'index']);

Route::post('/product-create', [VendorApiController::class, 'store']);


