<?php

namespace App\Services\Shopify\REST;

use App\Services\Shopify\BaseService;


class CustomerService extends BaseService
{
    function getCustomerInforFromShopify()
    {
        $query = "{
            customers(first:10){
                edges {
                    node {
                      id
                      email
                      firstName
                      lastName
                    }
                  }
            }
        }";

        $response = $this->getShop()->api()->graph($query);
        //$data = json_decode(['customer'], true);
        return $response['body'];
    }

    function getCustomerById($customerId)
    {
        $query = "{
            customer(id: \"gid://shopify/Customer/{$customerId}\") {
                email
                firstName
                lastName
                defaultAddress {
                  address1
                  city
                  province
                  zip
                  country
                }
              }
        }";
        $response = $this->getShop()->api()->graph($query);
        //$data = json_decode(['customer'], true);
        return $response['body'];
    }
}
