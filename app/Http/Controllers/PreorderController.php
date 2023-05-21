<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Preorder;

use Illuminate\Http\Request;
use App\Services\Shopify\REST\PreorderService;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PreorderController extends Controller
{
    public function getUserId()
    {
        $user = UserController::show();
        return $user->id;
    }

    public function index()
    {
        return Preorder::getPreorders($this->getUserId());
    }

    public function searchByCustomerName($customerName)
    {
        return PreOrder::getPreordersByCustomerName($this->getUserId(), $customerName);
    }

    public function store(Request $request) {

        $customer = Customer::createCustomer($request);
        // return $customer;
        return Preorder::createPreorder($request, $customer->id);
    }
}
