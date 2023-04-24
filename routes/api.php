<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/saveAll', [ProductController::class, 'saveAll']);

Route::get('/allproductsfromshopify', [ProductController::class, 'getProductsFromShopify']);

Route::get('/products/{productId}', [ProductController::class, 'show']);
Route::get('/customerInfo', [CustomerController::class, 'getCustomerInfo']);
Route::get('/customerInfo/{id}', [CustomerController::class, 'getCustomerInfoById']);
Route::get('/saveAll', [ProductController::class, 'saveAll']);
Route::prefix('/products')->group(function () {
    Route::get('/', [ProductController::class, 'getAllProducts']);
    Route::get('/{productId}', [ProductController::class, 'getProductById']);

});
