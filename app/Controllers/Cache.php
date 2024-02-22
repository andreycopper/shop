<?php

namespace Controllers;

use Models\Category;
use Models\Fias\District;
use Models\Page;
use Models\PriceType;
use Models\Product\Product;
use Models\Product\ProductImage;
use Models\Product\ProductStore;

class Cache extends Controller
{
    protected function before()
    {
    }

    protected function actionDefault()
    {
    }

    /**
     * Создает кэш каталога, меню главное и личного кабинета (вызывается кроном раз в час)
     * @param string $type
     * @return void
     */
    protected function actionMenu(string $type)
    {
        $time = time();
        $time += 60 * 60 * 24 * 7;

        switch ($type) {
            case 'main':
                $data = Page::getMainMenu();
                break;
            case 'personal':
                $data = Page::getPersonalMenu();
                break;
            case 'groups':
                $data = Category::getList();
                break;
        }

        if (!empty($data)) {
            $result = [
                'expiration' => $time,
                'data' => $data
            ];
            if (!is_dir(DIR_CACHE . "/menu")) mkdir(DIR_CACHE . "/menu");
            file_put_contents(DIR_CACHE . "/menu/{$type}", json_encode($result, JSON_UNESCAPED_UNICODE));
        }
    }

    /**
     * Создет кэш федеральных округов
     * @return void
     */
    protected function actionDistricts()
    {
        $time = time();
        $time += 60 * 60 * 24 * 7;
        $data = District::getList();

        if (!empty($data)) {
            $result = [
                'expiration' => $time,
                'data' => $data
            ];
            if (!is_dir(DIR_CACHE . "/location")) mkdir(DIR_CACHE . "/location");
            file_put_contents(DIR_CACHE . "/location/districts", json_encode($result, JSON_UNESCAPED_UNICODE));
        }
    }

    protected function actionProducts()
    {
        $time = time();
        $time += 60 * 60 * 24 * 7;
        $price_types = PriceType::getAll();

        foreach ($price_types as $price_type) {
            $data = Product::getPriceList(null, $price_type);

            if (!empty($data) && is_array($data)) {
                foreach ($data as $item) {
                    $item->images = ProductImage::getByProductId($item->id);
                    $item->stores = ProductStore::getQuantities($item->id);

                    $result = [
                        'expiration' => $time,
                        'data' => $item
                    ];
                    if (!is_dir(DIR_CACHE . "/product")) mkdir(DIR_CACHE . "/product");
                    if (!is_dir(DIR_CACHE . "/product/{$item->id}")) mkdir(DIR_CACHE . "/product/{$item->id}");
                    file_put_contents(DIR_CACHE . "/product/{$item->id}/{$item->id}_$price_type", json_encode($result, JSON_UNESCAPED_UNICODE));
                }
            }
        }
    }

    protected function actionProduct(int $id)
    {
        $time = time();
        $time += 60 * 60 * 24 * 7;
        $price_types = PriceType::getAll();

        foreach ($price_types as $price_type) {
            $data = Product::getPrice($id, $price_type);

            if (!empty($data)) {
                $result = [
                    'expiration' => $time,
                    'data' => $data
                ];
                if (!is_dir(DIR_CACHE . "/product")) mkdir(DIR_CACHE . "/product");
                if (!is_dir(DIR_CACHE . "/product/{$id}")) mkdir(DIR_CACHE . "/product/{$id}");
                file_put_contents(DIR_CACHE . "/product/{$id}/{$id}_{$price_type}", json_encode($result, JSON_UNESCAPED_UNICODE));
            }
        }
    }

    protected function actionGroups()
    {
        $time = time();
        $time += 60 * 60 * 24 * 7;
        $data = Category::getList();

        if (!empty($data) && is_array($data)) {
            foreach ($data as $item) {
                $result = [
                    'expiration' => $time,
                    'data' => $item
                ];
                if (!is_dir(DIR_CACHE . "/group")) mkdir(DIR_CACHE . "/group");
                file_put_contents(DIR_CACHE . "/group/{$item->link}", json_encode($result, JSON_UNESCAPED_UNICODE));

                if (!empty($item->parent_id)) {
                    if (!is_dir(DIR_CACHE . "/group/{$item->parent_id}")) mkdir(DIR_CACHE . "/group/{$item->parent_id}");
                    file_put_contents(DIR_CACHE . "/group/{$item->parent_id}/{$item->id}", json_encode($result, JSON_UNESCAPED_UNICODE));
                }
            }
        }
    }

    protected function actionPages()
    {
        $time = time();
        $time += 60 * 60 * 24 * 7;
        $data = Page::getList();

        if (!empty($data) && is_array($data)) {
            foreach ($data as $item) {
                $result = [
                    'expiration' => $time,
                    'data' => $item
                ];
                if (!is_dir(DIR_CACHE . "/page")) mkdir(DIR_CACHE . "/page");
                file_put_contents(DIR_CACHE . "/page/{$item->link}", json_encode($result, JSON_UNESCAPED_UNICODE));
            }
        }
    }
}
