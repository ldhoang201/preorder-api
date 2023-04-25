<?php

namespace App\Services\Shopify\REST;

use App\Services\Shopify\BaseService;
use App\Models\Preorder;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\Model;
use Spatie\FlareClient\Api;

class PreorderService extends BaseService
{

    public function getPreordersFromDB()
    {
        // $preorders = Preorder::all();
        // return $preorders;
        //paginate that each page have 10 preorders use offset paginate
        $preorders = Preorder::paginate(10);
        return $preorders;
    }

    public function getPreorderById($preorderId)
    {
        $preorder = Preorder::findOrFail($preorderId);
        if (!$preorder) {
            return [
                'error' => "Pre-order not found"
            ];
        }
        return $preorder;
    }

    public function getPreorderByCustomer($customerId)
    {
        $preorder = Preorder::findOrFail($customerId);
        if (!$preorder) {
            return [
                'error' => "Pre-order not found"
            ];
        }
        return $preorder;
    }
}
