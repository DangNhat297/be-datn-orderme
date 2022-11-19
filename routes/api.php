<?php


use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DishesController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\HeroWeddingMessageController;
use Illuminate\Support\Facades\Route;


Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

// client
Route::prefix('client')->group(function () {

    // cart
    Route::apiResource('cart', CartController::class);
    Route::post('cart/deleteMultiple', [CartController::class, 'Delete_Cart_By_Selection']);
});


Route::group(['middleware' => 'auth.jwt'], function ($routes) {

    // user
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'profile']);

    // admin
    Route::group(['prefix' => 'admin', 'middleware' => ['auth.admin']], function () {
        // category
        Route::apiResource('category', CategoryController::class);

        // dish
        Route::apiResource('dish', DishesController::class);

        // location
        Route::apiResource('location', LocationController::class);
    });
});

Route::prefix('hero-wedding')->group(function () {
    Route::apiResource('message', HeroWeddingMessageController::class);
});

Route::fallback(function () {
    return response()->json([
        'message' => 'Page Not Found'], 404);
});
