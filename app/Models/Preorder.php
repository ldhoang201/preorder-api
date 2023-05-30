<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\JsonResponse;

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

    public static function getPreorders($userId)
    {
        return Preorder::with('customer', 'variant')->where('user_id', $userId)->get();
    }

    public static function getPreordersByCustomerName($userId, $customerName)
    {
        return Preorder::whereHas('customer', function ($query) use ($customerName) {
            $query->where('name', 'ilike', '%' . $customerName . '%');
        })->where('user_id', $userId)->with('customer', 'variant')->get();
    }

    public static function createPreorder($variantId, $quantity, $userId, $customerId): JsonResponse
    {
        $preorder = Preorder::create([
            'customer_id' => $customerId,
            'user_id' => $userId,
            'variant_id' => $variantId,
            'quantity' => $quantity,
            'status' => 0
        ]);

        if ($preorder) {
            return response()->json(['message' => 'Preorder created successfully'], 200);
        } else {
            return response()->json(['message' => 'Failed to create preorder'], 500);
        }
    }

    public static function fulfillOrdersByVar($variantsId)
    {
        Preorder::whereIn('variant_id', $variantsId)->where('status', 0)->update(['status' => 1]);
    }

    public static function fulfillPreorders($preordersId)
    {
        $preorders = Preorder::with('variant')->where('status', 0)->whereIn('id', $preordersId)->get();

        foreach ($preorders as $preorder) {
            $quantity = $preorder->quantity;
            $variant = $preorder->variant;
            $newPreorder = max(0, $variant->preorder - $quantity);
            $newSold = $variant->sold + $quantity;
            $variant->update(['preorder' => $newPreorder, 'sold' => $newSold]);
            $preorder->update(['status' => 1]);
        }
    }
}
