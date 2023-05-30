<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Variant;

class Preorder extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'customer_id',
        'variant_id',
        'quantity',
        'status',
        'user_id'
    ];

    public function customer(): HasOne
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }

    public function variant(): HasOne
    {
        return $this->hasOne(Variant::class, 'id', 'variant_id');
    }

    public static function getPreorders($user_id)
    {
        return Preorder::with('customer', 'variant')->where('user_id', $user_id)->get();
    }

    public static function getPreordersByCustomerName($user_id, $customerName)
    {
        return Preorder::whereHas('customer', function ($query) use ($customerName) {
            $query->where('name', 'ilike', '%' . $customerName . '%');
        })->where('user_id', $user_id)->with('customer', 'variant')->get();
    }

    public static function createPreorder($variant_id, $quantity, $user_id, $customer_id)
    {
        Preorder::create([
            'customer_id' => $customer_id,
            'user_id' => $user_id,
            'variant_id' => $variant_id,
            'quantity' => $quantity,
            'status' => 0
        ]);
    }

    public static function fulfillOrdersByVar($variants_id)
    {
        foreach ($variants_id as $variant_id) {
            Preorder::where('variant_id', $variant_id)->where('status', 0)->update(['status' => 1]);
        }
    }

    public static function fulfillOrders($preorders_id)
    {
        foreach ($preorders_id as $preorder_id) {
            $preorder = Preorder::with('variant')->where('status', 0)->find($preorder_id);
            if ($preorder) {
                $quantity = $preorder->quantity;
                $variant = $preorder->variant;
                $newPreorder = max(0, $variant->preorder - $quantity);
                $newSold = $variant->sold + $quantity;
                $variant->update(['preorder' => $newPreorder, 'sold' => $newSold]);
                $preorder->update(['status' => 1]);
            }
        }
    }
}
