<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [

        'user_id',
        'title',
        'status',
        'image_src',
        'id',
        'date_start',
        'date_end',
        'vendor'
    ];

    public function variants()
    {
        return $this->hasMany(Variant::class, 'product_id', 'id');
    }

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
            'id' => $product['id'],
            'user_id' => auth()->user()->id,
            'image_src' => $product['image']['src'] ?? 'no_image',
            'title' => $product['title'],
            'vendor' => $product['vendor']
        ];

        self::updateOrCreate(['id' => $product['id']], $data);
    }


    public static function getAll($user_id)
    {
        return Product::select('title', 'status', 'date_start', 'date_end', 'vendor', 'image_src', 'id')->where('user_id', $user_id)->get();
    }

    public static function updateStatus($user_id, $product_id, $date_start, $date_end)
    {
        Product::where('id', $product_id)
            ->where('user_id', $user_id)->update([
                'status' => 1,
                'date_start' => $date_start,
                'date_end' => $date_end
            ]);
    }

    public static function searchByProductId($user_id, $product_id)
    {
        return Product::select('title', 'status', 'date_start', 'date_end', 'vendor', 'image_src', 'id')
            ->where('id', $product_id)
            ->where('user_id', $user_id)->first();
    }

    public static function searchByName($user_id, $name)
    {
        return Product::select('title', 'status', 'date_start', 'date_end', 'vendor', 'image_src', 'id')
            ->where('title', 'ilike', '%' . $name . '%')
            ->where('user_id', $user_id)->get();
    }

    public static function checkActive($product_id)
    {

        $variants = Product::with(['variants' => function ($query) {
            $query->select('product_id', 'id', 'stock');
        }])->where('id', $product_id)
            ->select('id', 'status', 'date_start', 'date_end')
            ->first();
        return $variants;
    }

    public static function getVariantsByProductId($user_id, $product_id)
    {
        // $product = Product::where('user_id', $user_id)->where('product_id', $product_id)->first();
        $variants = Product::with(['variants' => function ($query) {
            $query->select('*');
        }])->where('user_id', $user_id)->where('id', $product_id)->first();
        $fields = ['title', 'variants'];
        return collect($variants)->only($fields)->all();
    }

    public static function getMostSold($user_id)
    {
        $products = Product::join('variants', 'products.id', '=', 'variants.product_id')
            ->select('products.title', DB::raw('SUM(variants.sold) as total_sold'))
            ->where('user_id', $user_id)->groupBy('products.id')
            ->orderByDesc('total_sold')
            ->take(3)
            ->get();
        return $products;
    }

    public static function getLeastSold($user_id)
    {
        $products = Product::join('variants', 'products.id', '=', 'variants.product_id')
            ->select('products.title', DB::raw('SUM(variants.sold) as total_sold'))
            ->where('user_id', $user_id)->groupBy('products.id')
            ->orderBy('total_sold')
            ->take(3)
            ->get();
        return $products;
    }

    public static function activate($user_id, $request)
    {
        Product::where('id', $request->input('product_id'))
            ->where('user_id', $user_id)->update([
                'status' => 1,
                'date_start' => $request->input('date_start'),
                'date_end' => $request->input('date_end')
            ]);
        return Product::where('id', $request->input('product_id'))
            ->where('user_id', $user_id)->get();
    }

    public static function deactivate($user_id, $product_id)
    {
        Product::where('user_id', $user_id)->where('id', $product_id)
            ->update(['status' => 0]);
    }
}
