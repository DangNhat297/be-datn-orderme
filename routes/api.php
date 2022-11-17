<?php


use App\Http\Controllers\Admin\AuthController as AuthAdmin;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DishesController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Client\AuthController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\HeroWeddingMessageController;
use Illuminate\Support\Facades\Route;

//use App\Http\Controllers\File\UploadFileController;


Route::group(['middleware' => 'api'], function ($routes) {
});

Route::post('login', [AuthAdmin::class, 'login']);
Route::post('register', [AuthAdmin::class, 'register']);
Route::group(['prefix' => 'auth', 'middleware' => ['auth:sanctum']], function () {
    Route::controller(AuthAdmin::class)->group(function () {
        Route::get('profile', 'profile');
        Route::put('update', 'update');
    });
});

Route::prefix('admin')->group(function () {
    Route::apiResource('category', CategoryController::class);
    Route::apiResource('dish', DishesController::class);
    Route::apiResource('location', LocationController::class);

});
//Route::prefix('file')->group(function () {
//    Route::post('upload', [UploadFileController::class, 'uploadFileToS3']);
//});

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::prefix('client')->group(function () {
    Route::apiResource('cart', CartController::class);
    Route::post('cart/deleteMultiple', [CartController::class, 'Delete_Cart_By_Selection']);

    Route::group(['prefix' => 'auth', 'middleware' => []], function () {
        Route::controller(AuthController::class)->group(function () {
            Route::post('login', 'login');
            Route::get('profile/{id}', 'profile');
            Route::put('update/{id}', 'update');
        });
    });

});


Route::prefix('hero-wedding')->group(function () {
    Route::apiResource('message', HeroWeddingMessageController::class);
});

Route::fallback(function () {
    return response()->json([
        'message' => 'Page Not Found'], 404);
});
