<?php

 namespace App\Http\Controllers;

 use Illuminate\Http\Request;
 use Osiset\ShopifyApp\Traits\WebhookController as BaseWebhookController;
 use Osiset\ShopifyApp\Util;

 class WebhookController extends Controller
 {
     use BaseWebhookController;

     /**
      * Handles the product/update webhook.
      *
      * @param Request $request The incoming request.
      *
      * @return void
      */
     public function handleProductUpdate(string $type, Request $request)
     {
         // Handle the incoming webhook
         // Get the job class and dispatch
         $jobClass = Util::getShopifyConfig('job_namespace') . str_replace('-', '', ucwords($type, '-')) . 'Job';
         $jobData = json_decode($request->getContent());

         $jobClass::dispatch(
             $request->header('x-shopify-shop-domain'),
             $jobData
         )->onQueue(Util::getShopifyConfig('job_queues')['webhooks']);

         // Return a JSON response with a 200 status code
         return response()->json(['message' => 'Webhook received successfully'], 200);
     }
 }
