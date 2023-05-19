<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Variant;
use App\Http\Controllers\UserController;

class ProductController extends Controller
{

    public function getUserId()
    {
        $user = UserController::show();
        return $user->id;
    }

    public function test()
    {
        return Product::getProductsFromShopify();
    }

    // take data from Shoppify and store in database
    public function store()
    {
        $products = Product::getProductsFromShopify();

        foreach ($products as $product) {
            Product::saveProductInfo($product);
            $variants = $product['variants'];
            foreach ($variants as $variant) {
                Variant::saveVariantInfo($variant);
            }
        }
        return response()->json(['message' => 'Success'], 200);
    }

    // get all products in database
    public function index()
    {
        return Product::getAll($this->getUserId());
    }

    // activate or deactivate a product by product_id
    public function changeStatus($product_id, $stock, $date_start, $date_end)
    {
        return Product::updateStatus($this->getUserId(), $product_id, $stock, $date_start, $date_end);
    }

    public function searchByProductId($product_id)
    {
        $product = Product::searchByProductId($this->getUserId(), $product_id);
        if ($product) {
            return $product;
        } else {
            return response()->json(['message' => 'Product not found'], 404);
        }
    }

    public function searchByName($name)
    {
        $product =  Product::searchByName($this->getUserId(), $name);
        if (count($product) == 0) {
            return response()->json(['message' => 'Product not found'], 404);
        } else {
            return $product;
        }
    }

    public function checkActive($product_id)
    {
        return Product::checkActive($this->getUserId(), $product_id);
    }

    public function getVariants($product_id)
    {
        return Product::getVariantsByProductId($this->getUserId(), $product_id);
    }

    public function getBestSeller()
    {
        return Product::getMostSold($this->getUserId());
    }

    public function getWorstSeller()
    {
        return Product::getLeastSold($this->getUserId());
    }

    public function activate(Request $request)
    {
        Product::activate($this->getUserId(), $request);
        return Variant::setStock($request->input('variants_stock'));
    }
}
