<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PreorderController;
use App\Http\Controllers\WebhookController;
// use Osiset\ShopifyApp\Traits\WebhookController;

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
    Route::post('/save', [ProductController::class, 'save']);

    Route::get('/', [ProductController::class, 'getProducts']);
    Route::get('/variants/{id}', [ProductController::class, 'getVariants']);

    Route::prefix('/search')->group(function () {
        Route::get('/id/{id}', [ProductController::class, 'searchById']);
        Route::get('/name/{name}', [ProductController::class, 'searchByname']);
        Route::get('/pre/{name}', [ProductController::class, 'searchPre']);
    });

    Route::prefix('active')->group(function () {
        Route::get('/', [ProductController::class, 'getActiveProducts']);
        Route::get('/{id}', [ProductController::class, 'checkActive']);
    });

    Route::put('/deactivate/{id}', [ProductController::class, 'deactivate']);

    Route::get('/abc/get', [ProductController::class, 'getProductsFromShopify']);
    // Route::prefix('/{productId}')->group(function () {
    //     Route::delete('/', [ProductController::class], 'deleteProduct');
    //     Route::get('/detail', [ProductController::class, 'showVariants']);
    //     Route::get('/activate', [ProductController::class, 'activate']);
    //     Route::get('/deactivate', [ProductController::class, 'deactivate']);
    // });
});



// Route::delete('products/{productId}/del', [ProductController::class, 'deleteProduct']);

Route::post('/regiswebhook', [WebhookController::class, 'createWebhook'])->middleware('verify.shopify');
Route::get('/listwebhooks', [WebhookController::class, 'getListWebhooks'])->middleware('verify.shopify');
Route::delete('/removeWebhook/{webhookId}', [WebhookController::class, 'removeWebhook'])->middleware('verify.shopify');
