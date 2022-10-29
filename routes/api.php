<?php


    use App\Http\Controllers\Admin\CategoryController;
    use App\Http\Controllers\Admin\DishesController;
    use App\Http\Controllers\Admin\LocationController;
    use App\Http\Controllers\File\UploadFileController;
    use App\Http\Controllers\Client\CartController;

    use Illuminate\Support\Facades\Route;


 
Route::group(['middleware' => 'api'], function ($routes) {
});

Route::prefix('admin')->group(function () {
    Route::apiResource('category', CategoryController::class);
    Route::apiResource('dishes', DishesController::class);
    Route::apiResource('location', LocationController::class);
});
Route::prefix('file')->group(function () {
    Route::post('upload', [UploadFileController::class, 'uploadFileToS3']);
});

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

    Route::prefix('client')->group(function () {
        Route::apiResource('cart', CartController::class);

    });


    Route::fallback(function () {
        return response()->json([
            'message' => 'Page Not Found'], 404);
    });
