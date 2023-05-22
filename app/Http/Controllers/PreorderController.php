<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Preorder;
use Illuminate\Http\Request;
use App\Models\Variant;

class PreorderController extends Controller
{
    public function getUserId()
    {
        $user = UserController::show();
        return $user->id;
    }

    // get all preorders
    public function index()
    {
        return Preorder::getPreorders($this->getUserId());
    }

    // search preorders by customer name
    public function searchByCustomerName($customerName)
    {
        return PreOrder::getPreordersByCustomerName($this->getUserId(), $customerName);
    }

    // save preorders from sdk form
    public function store(Request $request)
    {

        $customer = Customer::createCustomer($request);
        $variant_id = $request->input('selectedVariantId');
        $quantity = $request->input('quantity');
        $user_id =  Variant::getUserIdByVariant($variant_id);

        Variant::deductStock($variant_id, $quantity);
        Variant::addPreorder($variant_id, $quantity);
        return Preorder::createPreorder($variant_id, $quantity, $user_id, $customer->id);
    }

    // cancel preorder
    public function cancel($preorder_id)
    {
    }

    public function fulfill($variant_id, $quantity)
    {
    }
}
