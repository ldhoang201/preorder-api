<?php

namespace App\Services\Shopify\REST;

use App\Http\Controllers\CustomerController;
use App\Services\Shopify\BaseService;
use App\Models\Product;
use App\Models\Variant;
use App\Models\Preorder;
use Illuminate\Http\Request;
use App\Models\Dp;
use App\Models\Discount;


class CustomerService extends BaseService
{
  function getCustomerInforFromShopify()
  {
    $query = "{
            customers(first:10){
                edges {
                    node {
                      id
                    }
                  }
            }
        }";

    $customers = $this->getShop()->api()->graph($query);
    //$data = json_decode(['customer'], true);
    return $customers['body']['data']['customers'];
  }

  function getCustomerById($customerId)
  {
    $query = "{
            customer(id: \"gid://shopify/Customer/{$customerId}\") {
                id
              }
        }";
    $customer = $this->getShop()->api()->graph($query);
    if (!$customer) {
      return [
        'error' => "Pre-order not found"
      ];
    }
    return $customer['body']['data']['customer'];
  }

  function handlePreorderRequest(Request $request){
    if (
      isset($request->customer_id) &&
      isset($request->user_id) &&
      isset($request->variant_id) &&
      isset($request->product_id) &&
      isset($request->quantity)
    ) {
      return $this->createPreOrder($request->customer_id,$request->user_id,$request->variant_id,$request->product_id,$request->quantity);
  }
}
  
  
  function createPreOrder($customer_id, $user_id , $variant_id , $product_id, $quantity){

    if ((!$customer_id) || (!$user_id) || (!$product_id) || (!$variant_id) || ($quantity)) {
        return [
            'error' => "Missing infomation such as CustomerId,UserId to create Preorder"
        ];
    }

    

    $variant = Variant::where('variant_id', $variant_id)->first();

    $product = Product::where('product_id', $product_id)->first();
    if((!$variant) || (!$product)){
        return ['error'=>'Model not found'];
    }
    $product -> decrement('quantity',1);
    return Preorder::create([
        'customer_id' => $customer_id,
        'variant_id'=>$variant_id,
        'preorder_date'=> date('Y-m-d'),
        'quantity'=> $quantity
    ]);

}
}
