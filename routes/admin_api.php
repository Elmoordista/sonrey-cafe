<?php

use App\Http\Controllers\AdminController;
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
});
Route::controller(CategoryController::class)->group(function () {
    Route::post('category/chagestatus', 'chageStatus');
});
Route::controller(AdminController::class)->group(function () {
    Route::post('admin/login', 'login');
});


Route::resources([
    'client' => ClientController::class,
    'product' => ProductController::class,
    'category' => CategoryController::class,
    'admin' => AdminController::class,
    'order' => OrderController::class,
]);