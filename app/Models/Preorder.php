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
}
