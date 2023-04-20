<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Preorder extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'customer_id',
        'variant_id',
        'preorder_date',
        'quantity'
    ];
}
