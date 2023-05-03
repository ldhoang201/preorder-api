<?php

namespace App\Http\Controllers;

use App\Models\Preorder;

use Illuminate\Http\Request;
use App\Services\Shopify\REST\PreorderService;

class PreorderController extends Controller
{
    public function getPreorders()
    {
        $preorders = Preorder::paginate(10);
        return $preorders;
    }

    public function getPreorderById(Request $request)
    {
        $preorder_id = $request->preorderId;
        $preorder = Preorder::findOrFail($preorder_id);

        if (!$preorder) {
            return [
                'error' => "Pre-order not found"
            ];
        }

        return $preorder;
    }


    public function getPreorderByCustomer(Request $request)
    {
        $customer_name = $request->customerName;
        $preorder = Preorder::where('customer_name', $customer_name);
        if (!$preorder) {
            return [
                'error' => "Pre-order not found"
            ];
        }
        return $preorder;
    }

    public function removePreorder(Request $request)
    {
        $preorder_id = $request->preorderId;
        $preorder = Preorder::findOrFail($preorder_id);
        $preorder->delete();
        return $preorder;
    }
}
