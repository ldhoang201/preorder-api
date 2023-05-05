<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Services\Shopify\REST\ShopService;
use App\Models\User;
use App\Models\Preorder;
use App\Models\Product;
use App\Models\Variant;
use App\Services\Shopify\REST\CustomerService;

class CustomerController extends Controller
{
    public function index()
    {
    }


    public function getCustomerById(Request $request)
    {
        $customer_id = $request->customerId;
        $customer = Customer::findOrFail($customer_id);
        return $customer;
    }



    function handlePreorderRequest(Request $request)
    {
        if (
            isset($request->customer_name) &&
            isset($request->customer_email) &&
            isset($request->customer_phone) &&
            isset($request->customer_address) &&
            isset($request->user_id) &&
            isset($request->variant_id) &&
            isset($request->product_id) &&
            isset($request->quantity)
        ) {
            return $this->createPreOrder(
                $request->customer_name,
                $request->customer_email,
                $request->customer_phone,
                $request->customer_address,
                $request->user_id,
                $request->variant_id,
                $request->product_id,
                $request->quantity
            );
        } else {
            return [
                'error' => "Missing infomation such as Customer Information,UserId to create Preorder"
            ];
        }
    }

    function saveCustomerInfo($user_id, $customer_name, $customer_email, $customer_phone, $customer_address)
    {
        Customer::updateOrCreate([
            'user_id' => $user_id,
            'customer_name' => $customer_name,
            'customer_email' => $customer_email,
            'customer_phone' => $customer_phone,
            'customer_address' => $customer_address
        ]);
    }


    function createPreOrder($customer_name, $customer_email, $customer_phone, $customer_address, $user_id, $variant_id, $product_id, $quantity)
    {

        if ((!$customer_name) || (!$customer_email) || (!$customer_phone) || !($customer_address) || (!$user_id) || (!$product_id) || (!$variant_id) || ($quantity)) {
            return [
                'error' => "Missing infomation such as Customer information,UserId to create Preorder"
            ];
        }

        $this->saveCustomerInfo($user_id, $customer_name, $customer_email, $customer_phone, $customer_address);

        $customer_id = Customer::where('customer_email', $customer_email)->first();

        $variant = Variant::where('variant_id', $variant_id)->first();

        $product = Product::where('product_id', $product_id)->first();
        if ((!$variant) || (!$product)) {
            return ['error' => 'Model not found'];
        }
        return Preorder::create([
            'customer_id' => $customer_id,
            'variant_id' => $variant_id,
            'preorder_date' => date('Y-m-d'),
            'quantity' => $quantity
        ]);
    }
}
