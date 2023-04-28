<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PreorderController;
use App\Http\Controllers\WebhookController;

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

Route::get('/customerInfo', [CustomerController::class, 'getCustomerInfo']);
Route::get('/customerInfo/{id}', [CustomerController::class, 'getCustomerInfoById']);



Route::prefix('/products')->middleware('verify.shopify')->group(function () {
    Route::get('/', [ProductController::class, 'showProducts']);
    Route::get('/saveAll', [ProductController::class, 'saveAll']);
    Route::get('/{productName}/search', [ProductController::class, 'searchProductsByName']);
    Route::get('/getActiveProducts', [ProductController::class, 'getActiveProducts']);
    Route::prefix('/{productId}')->group(function () {
        Route::get('/detail', [ProductController::class, 'showVariants']);
        Route::get('/activate', [ProductController::class, 'activate']);
        Route::get('/deactivate', [ProductController::class, 'deactivate']);
    });
});

Route::post('/webhook/product/update', [WebhookController::class, 'handleProductUpdate']);
