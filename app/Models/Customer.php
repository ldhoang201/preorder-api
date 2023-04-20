<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'customer_id',
        'shop_id',
        'email',
        'phone',
        'first_name',
        'last_name',
        'address'
    ];
}
