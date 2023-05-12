<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\UserController;

class Variant extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'product_id',
        'price',
        'option1',
        'option2',
        'title_var',
        'sku',
        'sold',
        'preorder'
    ];

    public static function saveVariantInfo($variant)
    {
        $data = [
            'id' => $variant['id'],
            'product_id' => $variant['product_id'],
            'title_var' => $variant['title'],
            'price' => $variant['price'],
            'option1' => $variant['option1'],
            'option2' => $variant['option2'],
            'sku' => $variant['sku']
        ];

        self::updateOrCreate(['id' => $variant['id']], $data);
    }
}
