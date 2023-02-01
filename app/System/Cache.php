<?php

namespace System;

use Models\Fias\District;
use Models\Group;
use Models\Page;
use Models\Product\Product;

class Cache
{
    /**
     * Возвращает каталог, главное и персональное меню из кэша (+)
     * @param $type - тип меню
     * @return array|false
     */
    public static function getMenu($type)
    {
        if (is_file(_CACHE . "/menu/{$type}") && filesize(_CACHE . "/menu/{$type}") > 0) {
            $data = json_decode(file_get_contents(_CACHE . "/menu/{$type}"));

            if (!empty($data->expiration) && $data->expiration > time() && !empty($data->data)) {
                $_SESSION['menu']["{$type}_expiration"] = $data->expiration;
                $_SESSION['menu'][$type] = (array)$data->data;
                return (array)$data->data;
            }
        }

        return false;
    }

    /**
     * Возвращает список федеральных округов из кэша (+)
     * @return array|false
     */
    public static function getDistricts()
    {
        if (is_file(_CACHE . "/location/districts") && filesize(_CACHE . "/location/districts") > 0) {
            $data = json_decode(file_get_contents(_CACHE . "/location/districts"));

            if (!empty($data->expiration) && $data->expiration > time() && !empty($data->data)) {
                return District::factory((array)$data->data);
            }
        }
        return false;
    }

    /**
     * Возвращает товар из кэша по его id (+)
     * @param int $id
     * @param int $price_type_id
     * @return array|false|object
     */
    public static function getProduct(int $id, int $price_type_id)
    {
        if (is_file(_CACHE . "/product/{$id}/{$id}_{$price_type_id}") &&
            filesize(_CACHE . "/product/{$id}/{$id}_{$price_type_id}") > 0)
        {
            $data = json_decode(file_get_contents(_CACHE . "/product/{$id}/{$id}_{$price_type_id}"));

            if (!empty($data->expiration) && $data->expiration > time() && !empty($data->data)) {
                return Product::factory($data->data);
            }
        }
        return false;
    }

    /**
     * Возвращает инфо о товаре из кэша по его id (+)
     * @param int $id
     * @return array|false|object
     */
    public static function getProductInfo(int $id)
    {
        if (is_file(_CACHE . "/product/{$id}/{$id}_info") && filesize(_CACHE . "/product/{$id}/{$id}_info") > 0) {
            $data = json_decode(file_get_contents(_CACHE . "/product/{$id}/{$id}_info"));

            if (!empty($data->expiration) && $data->expiration > time() && !empty($data->data)) {
                return Page::factory($data->data);
            }
        }
        return false;
    }

    /**
     * Возвращает инфо о категории из кэша по ее названию (+)
     * @param string $name
     * @return array|false|object
     */
    public static function getGroup(string $name)
    {
        if (is_file(_CACHE . "/group/{$name}") && filesize(_CACHE . "/group/{$name}") > 0) {
            $data = json_decode(file_get_contents(_CACHE . "/group/{$name}"));

            if (!empty($data->expiration) && $data->expiration > time() && !empty($data->data)) {
                return Group::factory($data->data);
            }
        }
        return false;
    }

    /**
     * Возвращает список подкатегорий
     * @param int $parent_id - id родительской категории
     * @return array|false
     */
    public static function getSubGroups(int $parent_id)
    {
        if (is_dir(_CACHE . "/group/{$parent_id}")) {
            $files = scandir(_CACHE . "/group/{$parent_id}");

            $res = [];
            foreach ($files as $file) {
                if ($file === '.' || $file === '..') continue;

                if (is_file(_CACHE . "/group/{$parent_id}/{$file}") && filesize(_CACHE . "/group/{$parent_id}/{$file}") > 0) {
                    $data = json_decode(file_get_contents(_CACHE . "/group/{$parent_id}/{$file}"));

                    if (!empty($data->expiration) && $data->expiration > time() && !empty($data->data)) {
                        $res[] = Group::factory($data->data);
                    }
                }
            }
            return $res;
        }
        return false;
    }

    /**
     * Возвращает инфо о странице из кэша по ее названию (+)
     * @param string $name
     * @return array|false|object
     */
    public static function getPage(string $name)
    {
        if (is_file(_CACHE . "/page/{$name}") && filesize(_CACHE . "/page/{$name}") > 0) {
            $data = json_decode(file_get_contents(_CACHE . "/page/{$name}"));

            if (!empty($data->expiration) && $data->expiration > time() && !empty($data->data)) {
                return Page::factory($data->data);
            }
        }
        return false;
    }
}
