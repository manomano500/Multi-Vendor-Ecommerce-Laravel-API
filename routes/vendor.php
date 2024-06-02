<?php

use App\Http\Controllers\api\v1\public\CategoryController;
use App\Http\Controllers\api\v1\VendorApiController;
use App\Models\Variation;


Route::get('/categories/parent', [CategoryController::class, 'getParentCategories']);
Route::get('/categories/{category}/children', [CategoryController::class, 'getChildrenCategories']);



Route::get('/attributes', [\App\Http\Controllers\AttributeController::class, 'index']);




Route::resource('/products', VendorApiController::class);


Route::get('test', function () {
    $variation= Variation::find(1);
return $variation->products;
});
