<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Osiset\ShopifyApp\Traits\WebhookController as ShopifyWebhookController;

class WebhookController extends Controller
{
    use ShopifyWebhookController;

    public function handleProductUpdate(Request $request)
    {
        $shopifyWebhook = new ShopifyWebhookController();
        $response = $shopifyWebhook->handle('product_update', $request);
    }
}
