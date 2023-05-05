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
  

}
