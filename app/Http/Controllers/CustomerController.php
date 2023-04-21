<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Shopify\REST\ShopService;
use App\Models\User;
use App\Services\Shopify\REST\CustomerService;

class CustomerController extends Controller
{
    public function index()
    {
    }

    public function getCustomerInfo()
    {
        $user = User::find(1);
        $customerService = new CustomerService($user);
        $response = $customerService->getCustomerInforFromShopify();
        return $response;
    }

    public function getCustomerInfoById($id)
    {
        $user = User::find(1);
        $customerService = new CustomerService($user);
        $response = $customerService->getCustomerbyId($id);
        return $response;
    }
}
