<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use PhpParser\Node\Expr\Cast\Object_;

class Preorder extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'customer_id',
        'variant_id',
        'preorder_date',
        'quantity',
        'status',
        'user_id'
    ];

    public function customer(): HasOne
    {
        return $this->hasOne(Customer::class);
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

    public static function createPreorder($request){
        
    }
}
