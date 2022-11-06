<?php


use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DishesController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Client\CartController;
use Illuminate\Support\Facades\Route;

//use App\Http\Controllers\File\UploadFileController;


Route::group(['middleware' => 'api'], function ($routes) {
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

//    Route::group(['prefix' => 'auth'], function() {
//        Route::post('login', [AuthController::class,'login']);
//        Route::get('profile/{id}', [AuthController::class,'profile']);
//        Route::put('update/{id}', [AuthController::class,'update']);
//    });
});


Route::fallback(function () {
    return response()->json([
        'message' => 'Page Not Found'], 404);
});
