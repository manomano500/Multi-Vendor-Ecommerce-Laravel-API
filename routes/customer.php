<?php


use App\Http\Controllers\Auth\RegisteredUserController;



//Route::get('/stores',)

Route::post('/become-vendor', [RegisteredUserController::class, 'becomeVendor'])
    ->middleware(['auth','auth:sanctum','role:customer'])
    ->name('become-vendor');

