<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RatingController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;

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





Route::prefix('ratings')->group(function () {
    Route::post('/rate/{productId}', [RatingController::class, 'rateProduct'])->middleware('auth');  //rating product  task 2 item 5
    Route::delete('/remove/{productId}', [RatingController::class, 'removeRating'])->middleware('auth');  //remove product  task 2 item 5
    Route::put('/change/{productId}', [RatingController::class, 'changeRating'])->middleware('auth');  //change product task 2 item 5
});



Route::get('/products', [ProductController::class, 'index']);

Route::post('/login', [AuthController::class, 'login']);



