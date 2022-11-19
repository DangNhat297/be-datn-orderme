<?php


use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DishesController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\HeroWeddingMessageController;
use Illuminate\Support\Facades\Route;

//Route::group(['prefix' => 'auth', 'middleware' => ['auth:sanctum']], function () {
//    Route::controller(AuthAdmin::class)->group(function () {
//        Route::get('profile', 'profile');
//        Route::put('update', 'update');
//    });
//});

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);


Route::group(['middleware' => 'auth.jwt'], function ($routes) {
    Route::post('me', [AuthController::class, 'profile']);
    Route::post('logout', [AuthController::class, 'logout']);


    //admin
    Route::group(['prefix' => 'admin', 'middleware' => ['auth.admin']], function () {
        Route::apiResource('category', CategoryController::class);
        Route::apiResource('dish', DishesController::class);
        Route::apiResource('location', LocationController::class);
    });

    //client
    Route::prefix('client')->group(function () {
        Route::apiResource('cart', CartController::class);
        Route::post('cart/deleteMultiple', [CartController::class, 'Delete_Cart_By_Selection']);

        //        Route::group(['prefix' => 'auth', 'middleware' => []], function () {
        //            Route::controller(AuthController::class)->group(function () {
        //                Route::post('login', 'login');
        //                Route::get('profile/{id}', 'profile');
        //                Route::put('update/{id}', 'update');
        //            });
        //        });

    });
});


Route::prefix('hero-wedding')->group(function () {
    Route::apiResource('message', HeroWeddingMessageController::class);
});

Route::fallback(function () {
    return response()->json([
        'message' => 'Page Not Found'], 404);
});
