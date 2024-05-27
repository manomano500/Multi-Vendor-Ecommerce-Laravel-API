<?php

use App\Http\Controllers\v1\CategoryController;
use App\Http\Controllers\v1\VendorApiController;
use App\Models\Attribute;

Route::get('/categories', [CategoryController::class, 'index']);

Route::get('/categories/parent', [CategoryController::class, 'getParentCategories']);






Route::post('/product-create', [VendorApiController::class, 'storeProduct']);
Route::get('/products', [\App\Http\Controllers\ProductController::class, 'index']);

Route::get('/attributes', [VendorApiController::class, 'fetchAttributes']);
Route::get('/values', [VendorApiController::class, 'fetchValues']);
