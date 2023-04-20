<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dp extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'discount_id',
        'product_id'
    ];
}
