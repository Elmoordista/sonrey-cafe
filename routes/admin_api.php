<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CartDetailController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| admin Routes
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

Route::controller(ProductController::class)->group(function () {
    Route::delete('product/{id}', 'deleteProduct');
    Route::post('product/searchproduct', 'searchproduct');
});
Route::controller(CategoryController::class)->group(function () {
    Route::post('category/chagestatus', 'chageStatus');
});
Route::controller(AdminController::class)->group(function () {
    Route::post('admin/login', 'login');
    Route::get('admin/checkuser', 'checkuser');
    Route::delete('admin/{id}/{role}', 'destroy');
});
Route::controller(CartDetailController::class)->group(function () {
    Route::post('cart_detail/deleteproduct', 'deleteProduct');
    Route::post('cart_detail/updatecart', 'updateCart');
});

Route::controller(OrderController::class)->group(function () {
    Route::post('order/update_status', 'update_status');
    Route::post('order/orderreport/{date}', 'orderReport');
    Route::get('order/orders', 'getOrderList');
});


Route::resources([
    'client' => ClientController::class,
    'product' => ProductController::class,
    'category' => CategoryController::class,
    'admin' => AdminController::class,
    'order' => OrderController::class,
    'cart' => CartController::class,
    'cart_detail' => CartDetailController::class,
]);