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
        'stock',
        'sold',
        'preorder'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
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

    public static function getVariants($user_id) {
        return Variant::with(['product' => function ($query) use ($user_id) {
            $query->select('id')->where('user_id', $user_id);
        }])->get();
    }

    public static function setStock($variants_stock)
    {
        foreach ($variants_stock as $variant) {
            Variant::where('id', $variant['id'])->update([
                'stock' => $variant['stock']
            ]);
        }
    }
}
