<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
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
    Route::post('client/register', 'register');
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


    Route::resources([
        'client' => ClientController::class,
        'product' => ProductController::class,
        'cart' => CartController::class,
        'order' => OrderController::class,
    ]);
});
