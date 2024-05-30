<?php

use App\Http\Controllers\api\v1\CategoryController;
use App\Http\Controllers\api\v1\VendorApiController;


Route::get('/categories/parent', [CategoryController::class, 'getParentCategories']);
Route::get('/categories/{category}/children', [CategoryController::class, 'getChildrenCategories']);



Route::get('/attributes', [\App\Http\Controllers\AttributeController::class, 'index']);




Route::resource('/products', VendorApiController::class);


Route::get('/variations', [\App\Http\Controllers\api\v1\VariationController::class, 'index']);

Route::get('/variations/{variationid}', [\App\Http\Controllers\api\v1\VariationController::class, 'show']);


//Route::get('/variations/{attribute}', [\App\Http\Controllers\api\v1\VariationController::class, 'showAttributeValues']);

