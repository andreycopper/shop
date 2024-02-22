<?php
namespace Utils;

use Models\Page;
use Models\Category;
use Models\Fias\District;
use Models\Product\Product;

class Cache
{
    /**
     * Возвращает текущего пользователя из кэша
     * @return mixed|null
     */
    public static function getUser()
    {
        return SessionCache::getUser();
    }

    /**
     * Возвращает меню из кэша (главное, каталог, кабинет)
     * @param $type - тип меню
     * @return array|false
     */
    public static function getMenu($type)
    {
        return SessionCache::getMenu($type) ?: FileCache::getMenu($type);
    }

    public static function saveMenu($type, $data)
    {
        SessionCache::saveMenu($type, $data);
        FileCache::saveMenu($type, $data);
    }

    public static function getSettings()
    {
        return SessionCache::getSettings() ?: FileCache::getSettings();
    }

    public static function saveSettings($data)
    {
        SessionCache::saveSettings($data);
        FileCache::saveSettings($data);
    }

    public static function getCategory($name)
    {
        return SessionCache::getCategory($name) ?: FileCache::getCategory($name);
    }

    public static function saveCategory($name, $data)
    {
        SessionCache::saveCategory($name, $data);
        FileCache::saveCategory($name, $data);
    }

    public static function getSubCategories(int $category_id)
    {
        return SessionCache::getSubCategories($category_id) ?: FileCache::getSubCategories($category_id);
    }

    public static function saveSubCategories($category_id, $data)
    {
        SessionCache::saveSubCategories($category_id, $data);
        FileCache::saveSubCategories($category_id, $data);
    }









































    /**
     * Возвращает список федеральных округов из кэша (+)
     * @return array|false
     */
    public static function getDistricts()
    {
        if (is_file(DIR_CACHE . "/location/districts") && filesize(DIR_CACHE . "/location/districts") > 0) {
            $data = json_decode(file_get_contents(DIR_CACHE . "/location/districts"));

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
        if (is_file(DIR_CACHE . "/product/{$id}/{$id}_{$price_type_id}") &&
            filesize(DIR_CACHE . "/product/{$id}/{$id}_{$price_type_id}") > 0)
        {
            $data = json_decode(file_get_contents(DIR_CACHE . "/product/{$id}/{$id}_{$price_type_id}"));

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
        if (is_file(DIR_CACHE . "/product/{$id}/{$id}_info") && filesize(DIR_CACHE . "/product/{$id}/{$id}_info") > 0) {
            $data = json_decode(file_get_contents(DIR_CACHE . "/product/{$id}/{$id}_info"));

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
        if (is_file(DIR_CACHE . "/group/{$name}") && filesize(DIR_CACHE . "/group/{$name}") > 0) {
            $data = json_decode(file_get_contents(DIR_CACHE . "/group/{$name}"));

            if (!empty($data->expiration) && $data->expiration > time() && !empty($data->data)) {
                return Category::factory($data->data);
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
        if (is_dir(DIR_CACHE . "/group/{$parent_id}")) {
            $files = scandir(DIR_CACHE . "/group/{$parent_id}");

            $res = [];
            foreach ($files as $file) {
                if ($file === '.' || $file === '..') continue;

                if (is_file(DIR_CACHE . "/group/{$parent_id}/{$file}") && filesize(DIR_CACHE . "/group/{$parent_id}/{$file}") > 0) {
                    $data = json_decode(file_get_contents(DIR_CACHE . "/group/{$parent_id}/{$file}"));

                    if (!empty($data->expiration) && $data->expiration > time() && !empty($data->data)) {
                        $res[] = Category::factory($data->data);
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
        if (is_file(DIR_CACHE . "/page/{$name}") && filesize(DIR_CACHE . "/page/{$name}") > 0) {
            $data = json_decode(file_get_contents(DIR_CACHE . "/page/{$name}"));

            if (!empty($data->expiration) && $data->expiration > time() && !empty($data->data)) {
                return Page::factory($data->data);
            }
        }
        return false;
    }
}
