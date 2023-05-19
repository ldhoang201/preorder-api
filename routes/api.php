<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PreorderController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\VariantController;
use App\Models\Preorder;

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
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/bestseller', [ProductController::class, 'getBestSeller']);
    Route::get('/worstseller', [ProductController::class, 'getWorstSeller']);
    Route::get('/active/{product_id}', [ProductController::class, 'checkActive']);
    Route::get('/variants/{product_id}', [ProductController::class, 'getVariants']);
    Route::put('/activate', [ProductController::class, 'activate']);


    Route::post('/', [ProductController::class, 'store']);

    Route::put('/change/{product_id}/{status}/{stock?}/{date_start?}/{date_end?}', [ProductController::class, 'changeStatus']);

    Route::prefix('/search')->group(function () {
        Route::get('/id/{product_id}', [ProductController::class, 'searchByProductId']);
        Route::get('/name/{name}', [ProductController::class, 'searchByname']);
    });
    Route::get('test', [ProductController::class, 'test']);
});


Route::prefix('/preorders')->middleware('verify.shopify')->group(function () {
    Route::get('/', [PreorderController::class, 'index']);
    Route::get('/{customerName}', [PreorderController::class, 'searchByCustomerName']);
});

Route::post('/regiswebhook', [WebhookController::class, 'createWebhook'])->middleware('verify.shopify');
Route::get('/listwebhooks', [WebhookController::class, 'getListWebhooks'])->middleware('verify.shopify');
Route::delete('/removeWebhook/{webhookId}', [WebhookController::class, 'removeWebhook'])->middleware('verify.shopify');
