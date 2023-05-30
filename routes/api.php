<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PreorderController;
use App\Http\Controllers\VariantController;

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

Route::middleware('verify.shopify')->group(function () {
    Route::prefix('/products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/bestseller', [ProductController::class, 'getBestSeller']);
        Route::get('/worstseller', [ProductController::class, 'getWorstSeller']);
        Route::get('/variants/{productId}', [ProductController::class, 'getVariants']);
        Route::put('/activate', [ProductController::class, 'activate']);
        Route::put('/deactivate/{productId}', [ProductController::class, 'deactivate']);
        Route::put('/fulfill', [ProductController::class, 'fulfillMany']);
        Route::put('/fulfill/{productId}', [ProductController::class, 'fulfillOne']);
        Route::post('/', [ProductController::class, 'store']);

        Route::prefix('/search')->group(function () {
            Route::get('/id/{productId}', [ProductController::class, 'searchByProductId']);
            Route::get('/name/{name}', [ProductController::class, 'searchByname']);
        });
    });

    Route::prefix('/variants')->group(function () {
        Route::get('/', [VariantController::class, 'index']);
    });

    Route::prefix('/preorders')->group(function () {
        Route::get('/', [PreorderController::class, 'index']);
        Route::get('/{customerName}', [PreorderController::class, 'searchByCustomerName']);
        Route::put('/fulfill', [PreorderController::class, 'fulfill']);
        // Route::put('/cancel/{preorder_id}', [PreorderController::class, 'cancel']);
    });
});

Route::prefix('/sdk')->group(function () {
    Route::get('/active/{productId}', [ProductController::class, 'checkActive']);
    Route::post('/preorders', [PreorderController::class, 'store']);
});

// Route::post('/regiswebhook', [WebhookController::class, 'createWebhook'])->middleware('verify.shopify');
// Route::get('/listwebhooks', [WebhookController::class, 'getListWebhooks'])->middleware('verify.shopify');
// Route::delete('/removeWebhook/{webhookId}', [WebhookController::class, 'removeWebhook'])->middleware('verify.shopify');
