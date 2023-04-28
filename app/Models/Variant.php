<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'product_id',
        'price',
        'size',
        'title_var',
        'color',
        'inventory',
    ];
}
