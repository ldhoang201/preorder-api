<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use App\Models\Product;
use App\Models\Variant;
use App\Http\Controllers\UserController;

class ProductController extends Controller
{

    // get products' info from Shopify
    public function getProductsFromShopify()
    {
        $user = auth()->user();
        $tempArr = $user->api()->rest('GET', '/admin/products.json');
        $response = data_get($tempArr, 'body.products');
        return $response;
    }

    public function getUserId()
    {
        $userController = new UserController;
        $user = $userController->show();
        return $user->id;
    }

    public function save()
    {
        $products = $this->getProductsFromShopify();
        // save products' info
        foreach ($products as $product) {
            Product::updateOrCreate([
                'product_id' => $product['id'],
                'user_id' => auth()->user()->id,
                'user_id' => auth()->user()->id,
                'image_src' => isset($product['image']['src']) ? $product['image']['src'] : 'no_image',
                'title' => $product['title']
            ]);
        }
        // save varients' info
        foreach ($products as $product) {
            $variants = $product['variants'];
            foreach ($variants as $variant) {
                Variant::updateOrCreate([
                    'id' => $variant['id'],
                    'product_id' => $variant['product_id'],
                    'title_var' => $variant['title'],
                    'price' => $variant['price'],
                    'option1' => $variant['option1'],
                    'option2' => $variant['option2'],
                ]);
            }
        };
        return 'Data saved successfully';
    }

    // show all products
    public function showProducts()
    {
        $product = Product::where('user_id', $this->getUserId())->get();
        return $product;
    }

    // search products by name
    public function search(Request $request)
    {
        $product = Product::where('title', 'ilike', '%' . $request->name . '%')
            ->where('user_id', $this->getUserId())
            ->where('status', 1)->take(5)->get();
        return $product;
    }

    // deactivate a product by id
    public function deactivate(Request $request)
    {
        $product = Product::where('product_id', $request->id)->first();
        $product->update(['status' => 0]);
        return $product->product_id . ' is deactivated';
    }

    // show all variants by product id
    public function showVariants(Request $request)
    {
        $variants = Variant::where('product_id', $request->id)->get();
        return $variants;
    }

    // activate a product by its id
    public function activate(Request $request)
    {
        $product = Product::where('product_id', $request->productId)->first();
        $product->update(['status' => 1]);
        return $product->product_id . ' is activated';
    }

    // get list of active products
    public function getActiveProducts() //lay ra nhung san pham co the pre-order
    {
        $products = Product::where('status', 1)->get(); //status = 1 la active = 0 la inactive
        return $products;
        $products = Product::where('status', 1)->get(); //status = 1 la active = 0 la inactive
        return $products;
    }
}
