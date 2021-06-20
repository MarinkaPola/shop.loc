<?php

use App\Http\Controllers\ReviewController;
use Illuminate\Http\Request;

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GoodController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SaleController;


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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//  return $request->user();
//});
//Auth routes

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');


Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/user/basket-now', [UserController::class, 'basket_now']);
    Route::apiResource('user', UserController::class);
    Route::apiResource('category', CategoryController::class);
    Route::apiResource('area', AreaController::class)->except(['index']);
    Route::apiResource('order', OrderController::class);
    Route::post('/good/in-basket', [OrderController::class, 'good_in_basket']);
    Route::put('/good/out-basket', [OrderController::class, 'good_out_basket']);
    Route::apiResource('goods', GoodController::class)->except(['index', 'show']);
    Route::apiResource('/goods/{good}/reviews', ReviewController::class)->only(['index', 'store']);
    Route::apiResource('reviews', ReviewController::class)->only(['show', 'update', 'destroy']);
    Route::apiResource('sale', SaleController::class);


//Route::get('/send-notification', [OrderController::class, 'sendOrderAcceptedNotification']);
});

Route::fallback([AuthController::class, 'fallback']);
Route::apiResource('goods', GoodController::class)->only(['index', 'show']);
Route::apiResource('area', AreaController::class)->only(['index']);
