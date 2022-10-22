<?php

use App\Http\Controllers\Admin\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Admin\DishesController;
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

Route::group(['middleware' => 'api'], function ($routes) {
    Route::get('/dishes', [ProductController::class, 'index']);
});

Route::prefix('admin')->group(function(){
    Route::apiResource('category', CategoryController::class);
    Route::apiResource('dishes', DishesController::class);
    Route::post('dishes/{id}', [DishesController::class,'update']);
});

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
