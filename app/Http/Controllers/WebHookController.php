<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Osiset\ShopifyApp\Traits\WebhookController as ShopifyWebhookController;

class WebhookController extends Controller
{
    use ShopifyWebhookController;

    public function createWebhook()
    {
        $user = auth()->user();
        $tempArr = $user->api()->rest('POST', '/admin/webhooks.json', [

            'webhook' =>
            [
                'topic' => 'products/update',
                'address' => 'https://f667-2405-4803-fca8-7020-b863-e445-630c-b657.ngrok-free.app/products-update',
                'format' => 'json'
            ]
        ]);

        $tempArr = $user->api()->rest('GET', '/admin/webhooks.json');
        return $tempArr;
    }

    public function getListWebhooks()
    {
        $user = auth()->user();
        $tempArr = $user->api()->rest('GET', '/admin/webhooks.json');
        return $tempArr;
    }

    public function removeWebhook(Request $request)
    {
        $user = auth()->user();
        $user->api()->rest('DELETE', '/admin/webhooks/' . $request->webhookId . '.json');
        $tempArr = $user->api()->rest('GET', '/admin/webhooks.json');
        return $tempArr;
    }

    public function getDataFromWebhook()
    {
    }
}
