<?php

namespace App\Services\Shopify\REST;

use App\Services\Shopify\BaseService;
use Illuminate\Support\Arr;

class ShopService extends BaseService
{
    /**
     * Get shop data from shopify
     *
     * @return array
     */
    protected function getShopData()
    {
        $response = $this->getShop()->api()->rest('GET', '/admin/shop.json');
        return data_get($response, 'body.shop');
    }

    /**
     * Get shop profile from shopify and transform in user data
     *
     * @return array
     */
    public function getShopProfile()
    {
        $data = $this->getShopData();

        $duplicated_fields = [
            'id',
            'name',
            'email',
            'created_at',
            'updated_at',
        ];

        foreach ($duplicated_fields as $field) {
            $data["shop_$field"] = Arr::get($data, $field);
        }

        return Arr::except($data, $duplicated_fields);
    }
}
