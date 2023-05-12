<?php

namespace App\Http\Controllers;

use App\Models\Preorder;

use Illuminate\Http\Request;
use App\Services\Shopify\REST\PreorderService;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PreorderController extends Controller
{

    public function index()
    {
        $preorder = Preorder::with('customer', 'variant')->get();
        return response()->json($preorder);
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
}
