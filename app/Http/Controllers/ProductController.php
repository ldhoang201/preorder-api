<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Variant;
use App\Models\Preorder;

class ProductController extends Controller
{

    public function getUserId()
    {
        $user = UserController::show();
        return $user->id;
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

    // search product by product_id
    public function searchByProductId($productId)
    {
        $product = Product::searchByProductId($this->getUserId(), $productId);

        if ($product) {
            return $product;
        } else {
            return response()->json(['message' => 'Product not found'], 404);
        }
    }

    // search product by name
    public function searchByName($name)
    {
        $product = Product::searchByName($this->getUserId(), $name);

        if ($product->isEmpty()) {
            return response()->json(['message' => 'Product not found'], 404);
        } else {
            return $product;
        }
    }

    // get product's status, variants' stock, date start and end
    public function checkActive($productId)
    {
        return Product::checkActive($productId);
    }

    // get all variants by product_id
    public function getVariants($productId)
    {
        return Product::getVariantsByProductId($this->getUserId(), $productId);
    }

    // get best seller products
    public function getBestSeller()
    {
        return Product::getMostSold($this->getUserId());
    }

    // get worst seller products
    public function getWorstSeller()
    {
        return Product::getLeastSold($this->getUserId());
    }

    // activate products for preorder
    public function activate(Request $request)
    {
        $variantsStock = $request->input('variants_stock');
        Product::activate($this->getUserId(), $request);
        return Variant::setStock($variantsStock);
    }

    // deactivate products
    public function deactivate($productId)
    {
        Product::deactivate($this->getUserId(), $productId);
        // return Variant::setStock($product_id, 1);
    }

    // fulfill one product
    public function fulfillOne($productId)
    {
        $product = Product::getVariantsByProductId($this->getUserId(), $productId);
        $variantsId = Variant::extractId($product['variants']);

        Variant::fulfillVar($variantsId);
        Preorder::fulfillOrdersByVar($variantsId);
    }

    // fulfill multi products
    public function fulfillMany(Request $request)
    {
        $productsId = $request->json()->all();
        foreach ($productsId as $productId) {
            $this->fulfillOne($productId);
        }
    }
}
