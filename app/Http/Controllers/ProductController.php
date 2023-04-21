<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use App\Services\Shopify\REST\ProductService;
use App\Services\Shopify\REST\ShopService;
use App\Models\Product;






class ProductController extends Controller
{

    public function getAllProducts()
    {
        $user = User::find(1);
        $productService = new ProductService($user);
        $response = $productService->getAllProducts();
        return $response;
    }
    public function getProductById($productId)
    {
        $user = User::find(1);
        $productService = new ProductService($user);
        $response = $productService->getProductById($productId);
        return $response;
    }

    public function saveProducts()
    {
        $product = $this->index();
        $user = User::find(1);
        $shopService = new ShopService($user);
        $shop_id = $shopService->getShopProfile();

    }
}
