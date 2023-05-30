<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Preorder;
use App\Models\Variant;
use Illuminate\Http\Request;

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
        $variantId = $request->input('selectedVariantId');
        $quantity = $request->input('quantity');
        $userId = Variant::getUserIdByVariant($variantId);

        Variant::deductStock($variantId, $quantity);
        Variant::addPreorder($variantId, $quantity);

        return Preorder::createPreorder($variantId, $quantity, $userId, $customer->id);
    }

    // fulfill preorders by preorder_id
    public function fulfill(Request $request)
    {
        $preorderIds = $request->json()->all();
        Preorder::fulfillPreorders($preorderIds);
        return response()->json(['message' => 'Success'], 200);
    }
}
