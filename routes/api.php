<?php

use App\Http\Controllers\AdminInfoController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CartDetailController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::controller(ClientController::class)->group(function () {
    Route::post('client/login', 'login');
    Route::post('client/porgot_pass', 'porgot_pass');
    Route::post('client/resetpassword', 'resetPassword');
    Route::post('client/register', 'register');
});

Route::controller(OrderController::class)->group(function () {
    Route::post('order/sendNotification', 'sendNotification');
    Route::post('order/update_status', 'update_status');
});

Route::middleware('auth:client')->group(function () {
 
    
    Route::controller(CartController::class)->group(function () {
        Route::get('cart/usercart', 'usercart');
        Route::post('cart/addtocart', 'addtocart');
        Route::get('cart/getcart', 'getcart');
    });
    
    Route::controller(OrderController::class)->group(function () {
        Route::post('order/addorder', 'addorder');
        Route::get('order/getorder', 'getuserorder');
    });

    Route::controller(ClientController::class)->group(function () {
        Route::get('client/info', 'info');
    });

    Route::controller(ProductController::class)->group(function () {
        Route::get('product/getproducts', 'getProducts');
        Route::post('product/searchprodapp', 'searchprodApp');
    });

    Route::controller(CartDetailController::class)->group(function () {
        Route::post('cart_detail/deleteproduct', 'deleteProduct');
        Route::post('cart_detail/updatecart', 'updateCart');
    });

    Route::resources([
        'client' => ClientController::class,
        'product' => ProductController::class,
        'cart' => CartController::class,
        'order' => OrderController::class,
        'feedback' => FeedbackController::class,
        'admininfo' => AdminInfoController::class,
        'cart_detail' => CartDetailController::class,
        'announcement' => AnnouncementController::class,
    ]);
});
