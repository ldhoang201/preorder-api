<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'user_id',
        'title',
        'status',
        'image_src',
        'id',
        'date_start',
        'date_end',
        'vendor',
        'stock'
    ];

    public static function getProductsFromShopify()
    {
        $user = auth()->user();
        $tempArr = $user->api()->rest('GET', '/admin/products.json');
        $response = data_get($tempArr, 'body.products');
        return $response;
    }

    public static function saveProductInfo($product)
    {
        $data = [
            'product_id' => $product['id'],
            'user_id' => auth()->user()->id,
            'image_src' => $product['image']['src'] ?? 'no_image',
            'title' => $product['title'],
            'vendor' => $product['vendor']
        ];

        self::updateOrCreate(['product_id' => $product['id']], $data);
    }


    public static function getAll($user_id)
    {
        return Product::select('title', 'status', 'date_start', 'date_end', 'vendor', 'image_src', 'product_id')->where('user_id', $user_id)->get();
    }

    public static function updateStatus($user_id, $product_id, $stock, $date_start, $date_end)
    {
        Product::where('product_id', $product_id)
            ->where('user_id', $user_id)->update([
                'status' => 1,
                'stock' => $stock,
                'date_start' => $date_start,
                'date_end' => $date_end
            ]);
    }

    public static function searchByProductId($user_id, $product_id)
    {
        return Product::select('title', 'status', 'date_start', 'date_end', 'vendor', 'image_src', 'product_id')
            ->where('product_id', $product_id)
            ->where('user_id', $user_id)->first();
    }

    public static function searchByName($user_id, $name)
    {
        return Product::select('title', 'status', 'date_start', 'date_end', 'vendor', 'image_src', 'product_id')
            ->where('title', 'ilike', '%' . $name . '%')
            ->where('user_id', $user_id)->get();
    }

    public static function checkActive($user_id, $product_id)
    {
        $product = Product::where('product_id', $product_id)
            ->where('user_id', $user_id)->first();
        return response()->json([
            'status' => $product->status,
            'name' => $product->name,
        ]);
    }
}
