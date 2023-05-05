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
                'address' => ' https://6e56-2405-4802-3f66-1660-a4c2-2c7f-b3c-fd38.ngrok-free.app/products-update',
                'format' => 'json'
            ]
        ]);

        return $tempArr;
    }

    public function getListWebhooks()
    {
        $user = auth()->user();
        $tempArr = $user->api()->rest('GET', '/admin/webhooks.json');
        return $tempArr;
    }

    public function removeWebhook($webhook_id)
    {
        $user = auth()->user();
        $tempArr = $user->api()->rest('DELETE', '/admin/webhooks/' . $webhook_id . '.json');
        return $tempArr;
    }

    public function getDataFromWebhook()
    {
    }
}
