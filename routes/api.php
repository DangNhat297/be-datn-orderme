<?php


use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\DishesController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\OrderController as AdminOrder;
use App\Http\Controllers\Admin\ProgramController as AdminProgramController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\StatisticalController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Chief\OrderController as ChiefOrder;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\CategoryController as CategoryClient;
use App\Http\Controllers\Client\CouponController as ClientCouponController;
use App\Http\Controllers\Client\DishController;
use App\Http\Controllers\Client\LocationController as LocationClient;
use App\Http\Controllers\Client\OrderController as ClientOrder;
use App\Http\Controllers\Client\ProgramController;
use App\Http\Controllers\Hero\HeroWeddingGuestController;
use App\Http\Controllers\Hero\HeroWeddingMessageController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\Shipper\OrderController as ShipperOrder;
use Illuminate\Support\Facades\Route;

// Authenticate
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('check-user-phone', [AuthController::class, 'checkPhone']);

// chat
Route::apiResource('chat', ChatController::class);
Route::get('chat/typing', [ChatController::class, 'onTypingChat']);
Route::get('chat-by-user/{phone}', [ChatController::class, 'getChatByUser']);
Route::get('room/message-not-seen-by-user/{phone}', [RoomController::class, 'getMessageNotSeenByUser']);

// client
Route::prefix('client')->group(function () {

    // cart
    Route::apiResource('cart', CartController::class);
    Route::post('cart/deleteMultiple', [CartController::class, 'Delete_Cart_By_Selection']);

    // category
    Route::apiResource('category', CategoryClient::class)->only('index');

    // dish
    Route::apiResource('dish', DishController::class);
    Route::get('dish/by-category/{id}', [DishController::class, 'by_category']);

    // location
    Route::apiResource('location', LocationClient::class);

    // order
    Route::apiResource('order', ClientOrder::class)->except(['destroy']);
    Route::get('/order/{order}/payment', [ClientOrder::class, 'payment']);
    Route::get('/return-vnpay', [ClientOrder::class, 'returnPaymentVNP'])->name('return.ipn.vnpay');

    // Program
    Route::get('/program', [ProgramController::class, 'show']);
    Route::get('/programs', [ProgramController::class, 'index']);

    // Coupon
    Route::apiResource('coupon', ClientCouponController::class);
});

Route::group(['middleware' => 'auth:sanctum'], function ($routes) {

    // account
    Route::get('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'profile']);

    /* ==== Chief role ====*/
    Route::group(['prefix' => 'chief', 'middleware' => 'auth.chief'], function () {
        Route::apiResource('order', ChiefOrder::class);
    });

    /* ==== Shipper role ====*/
    Route::group(['prefix' => 'shipper', 'middleware' => 'auth.shipper'], function () {
        Route::apiResource('order', ShipperOrder::class);
    });

    /* ==== Admin role ====*/
    Route::group(['prefix' => 'admin', 'middleware' => 'auth.admin'], function () {
        // category
        Route::apiResource('category', CategoryController::class);

        // dish
        Route::apiResource('dish', DishesController::class);

        // location
        Route::apiResource('location', LocationController::class);

        // setting
        Route::apiResource('setting', SettingController::class);

        // order
        Route::put('order/refund/{order}', [AdminOrder::class, 'refundVNP']);

        Route::apiResource('order', AdminOrder::class);

        // user
        Route::apiResource('user', UserController::class);

        //room chat
        Route::apiResource('room', RoomController::class);
        Route::get('chat-by-room/{id}', [ChatController::class, 'getChatByRoom']);

        // coupon
        Route::apiResource('coupon', CouponController::class);

        //program
        Route::apiResource('program', AdminProgramController::class);

        //statistical
        Route::apiResource('statistical', StatisticalController::class)->only('index');
        Route::group(['prefix' => 'statistical'], function () {
            Route::get('all-table', [StatisticalController::class, 'statistical_count_table']);
            Route::get('category-table', [StatisticalController::class, 'statistical_table_category']);
            Route::get('flash-sale', [StatisticalController::class, 'flashSale_product_statistics']);
        });

        //notification
        Route::apiResource('notification', NotificationController::class);
    });
});

Route::prefix('hero-wedding')->group(function () {
    Route::apiResource('message', HeroWeddingMessageController::class);
    Route::apiResource('guest', HeroWeddingGuestController::class);
});

Route::fallback(function () {
    return response()->json([
        'message' => 'Page Not Found'
    ], 404);
});
