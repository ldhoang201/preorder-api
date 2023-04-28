<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use App\Services\Shopify\REST\ProductService;
use App\Services\Shopify\REST\ShopService;
use App\Models\Product;
use App\Models\Variant;

class ProductController extends Controller
{

    public function index()
    {
        // $user = User::find(1);
        // $productService = new ProductService($user);
        // $response = $productService->getAllProducts();
        // return $response;


        $response = auth()->user()->api()->rest('GET', '/admin/products.json');
        return data_get($response, 'body.products');
    }

    public function getProduct($productId)
    {
        $user = User::find(1);
        $productService = new ProductService($user);
        $response = $productService->getProductById($productId);
        return $response;
    }

    public function getProductsFromShopify()
    {
        $user = auth()->user();
        $tempArr = $user->api()->rest('GET', '/admin/products.json');
        $response = data_get($tempArr, 'body.products');
        return $response;
    }

    public function saveAll()
    {
        $products = $this->getProductsFromShopify();
        foreach ($products as $product) {
            Product::updateOrCreate([
                'product_id' => $product['id'],
                'user_id' => auth()->user()->id,
                'image_src' => isset($product['image']['src']) ? $product['image']['src'] : 'no_image',
                'title' => $product['title']
            ]);
        }
        // // save varients' info
        foreach ($products as $product) {
            $variants = $product['variants'];
            foreach ($variants as $variant) {
                Variant::updateOrCreate([
                    'id' => $variant['id'],
                    'product_id' => $variant['product_id'],
                    'title_var' => $variant['title'],
                    'price' => $variant['price'],
                    'size' => $variant['option1'],
                    'color' => $variant['option2'],
                ]);
            }
        };
        return 'Data saved successfully';
    }

    public function activate(Request $request)
    {
        $product = Product::where('product_id', $request->productId)->first();
        $product->update(['status' => 1]);
        return $product->product_id . ' is activated';
    }

    public function deactivate(Request $request)
    {
        $product = Product::where('product_id', $request->productId)->first();
        $product->update(['status' => 0]);
        return $product->product_id . ' is deactivated';
    }

    public function getActiveProducts() //lay ra nhung san pham co the pre-order
    {
        $products = Product::where('status', 1)->get(); //status = 1 la active = 0 la inactive
        return $products;
    }
}
