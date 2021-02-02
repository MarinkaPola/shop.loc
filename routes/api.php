<?php

use Illuminate\Http\Request;

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GoodController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\OrderController;
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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
  //  return $request->user();
//});
//Auth routes

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');;


Route::group(['middleware' => ['auth:sanctum']], function () {
Route::apiResource('user', UserController::class);
Route::apiResource('category', CategoryController::class);
Route::apiResource('area', AreaController::class);
Route::apiResource('good', GoodController::class);
Route::apiResource('order', OrderController::class);
Route::post('/good-in-basket', [GoodController::class, 'good_in_basket']);
Route::put('/good-out-basket', [GoodController::class, 'good_out_basket']);
Route::get('/good-sort-by-price', [GoodController::class, 'good_sort_by_price']);
Route::get('/good-sort-by-desc-price', [GoodController::class, 'good_sort_by_desc_price']);
Route::get('/good-sort-by-sale', [GoodController::class, 'good_sort_by_sale']);
Route::get('/good-sort-by-desc-sale', [GoodController::class, 'good_sort_by_desc_sale']);

});

Route::fallback( [AuthController::class, 'fallback']);
