<?php

namespace App\Services\Shopify;

use Osiset\ShopifyApp\Contracts\ShopModel;

/**
 * Shopify service base class
 */
abstract class BaseService
{
    /**
     * @var \Osiset\ShopifyApp\Contracts\ShopModel Shop instance
     */
    private ShopModel $shop;

    /**
     * Create new service instance
     *
     * @param \Osiset\ShopifyApp\Contracts\ShopModel Shop instance
     */
    public function __construct(ShopModel $shop = null)
    {
        if ($shop) {
        }
        $this->shop = $shop ? $shop : auth()->user();
    }

    /**
     * Get shop
     *
     * @return \App\Models\User|\Osiset\ShopifyApp\Contracts\ShopModel
     */
    public function getShop()
    {
        return $this->shop ? $this->shop : auth()->user();
    }
}
