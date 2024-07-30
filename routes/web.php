<?php

use App\Http\Controllers\api\GoogleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

// routes/web.php


Route::get('login/google', [GoogleController::class, 'redirectToGoogle']);
Route::get('callback', [GoogleController::class, 'handleGoogleCallback']);




Route::get('/logged-in', function () {
    return view('redirect.google-logged-in');
});

//Route::get('/products', [\App\Http\Controllers\api\v1\VendorProductController::class, 'index']);



require __DIR__.'/auth.php';
