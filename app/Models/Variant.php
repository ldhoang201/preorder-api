<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\UserController;
use Illuminate\Contracts\Database\Eloquent\Builder;

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

    public function product()
    {
        return $this->belongsTo(Product::class, 'id', 'product_id');
    }

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

    public static function getVariantsByProductId($user_id, $product_id)
    {
        // return Variant::where('product_id', $product_id)
        //     ->with(['products' => function ($querry) use ($user_id) {
        //         $querry->where('user_id', '=', $user_id)->select('title');
        //     }])->select('id', 'product_id', 'price', 'option1', 'option2', 'title_var', 'sku', 'sold', 'preorder')
        //     ->get();
        $test = Variant::with(
            ['product' => function (Builder $query) {
                $query->select('product_id', 'title');
            }]
        )->where('product_id', $product_id)->get();
        return $test;
        // ->where('product_id', $product_id)
    }
}
