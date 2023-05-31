<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
        $response = $user->api()->rest('GET', '/admin/products.json');
        return data_get($response, 'body.products');
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

    public static function getAll($userId)
    {
        return Product::select('title', 'status', 'date_start', 'date_end', 'vendor', 'image_src', 'id')
            ->where('user_id', $userId)
            ->get();
    }

    public static function searchByProductId($userId, $productId)
    {
        return Product::select('title', 'status', 'date_start', 'date_end', 'vendor', 'image_src', 'id')
            ->where('id', $productId)
            ->where('user_id', $userId)
            ->first();
    }

    public static function searchByName($userId, $name)
    {
        return Product::select('title', 'status', 'date_start', 'date_end', 'vendor', 'image_src', 'id')
            ->where('title', 'ilike', '%' . $name . '%')
            ->where('user_id', $userId)
            ->get();
    }

    public static function checkActive($productId)
    {
        return Product::with(['variants' => function ($query) {
            $query->select('product_id', 'id', 'stock', 'title_var');
        }])
            ->where('id', $productId)
            ->select('id', 'status', 'date_start', 'date_end')
            ->first();
    }

    public static function getVariantsByProductId($userId, $productId)
    {
        $variants = Product::with(['variants' => function ($query) {
            $query->select('*');
        }])
            ->where('user_id', $userId)
            ->where('id', $productId)
            ->first();

        return collect($variants)->only(['title', 'variants'])->all();
    }

    public static function getMostSold($userId)
    {
        $query = Product::withSum('variants', 'sold')
            ->where('user_id', $userId)
            ->where('status', 1)
            ->whereHas('variants', function ($query) {
                $query->where('sold', '>', 0);
            })
            ->orderByDesc('variants_sum_sold');

        if ($query->count() >= 3) {
            return $query->take(3)->get();
        }

        return $query->get();
    }

    public static function getLeastSold($userId)
    {
        return Product::withSum('variants', 'sold')
            ->where('user_id', $userId)
            ->where('status', 1)
            ->orderBy('variants_sum_sold')
            ->take(3)
            ->get();
    }

    public static function activate($userId, $request)
    {
        $productId = $request->input('product_id');
        $dateStart = Carbon::parse($request->input('date_start'), 'UTC')->setTimezone('Asia/Ho_Chi_Minh');
        $dateEnd = Carbon::parse($request->input('date_end'), 'UTC')->setTimezone('Asia/Ho_Chi_Minh');

        Product::where('id', $productId)
            ->where('user_id', $userId)
            ->update([
                'status' => 1,
                'date_start' => $dateStart,
                'date_end' => $dateEnd
            ]);

        return Product::where('id', $productId)
            ->where('user_id', $userId)
            ->get();
    }

    public static function deactivate($userId, $productId)
    {
        Product::where('user_id', $userId)
            ->where('id', $productId)
            ->update(['status' => 0]);
    }
}
