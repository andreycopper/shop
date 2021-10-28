<?php

namespace Models;

use Models\Product\ProductStore;
use System\Db;
use Exceptions\DbException;

class Product extends Model
{
    protected static $table = 'products';
    public $id;
    public $xml_id;
    public $ie_id;
    public $name;
    public $active;
    public $active_from;
    public $active_to;
    public $articul;
    public $group_id;
    public $vendor_id;
    public $preview_image;
    public $preview_text;
    public $preview_text_type_id;
    public $detail_image;
    public $detail_text;
    public $detail_text_type_id;
    public $is_hit;
    public $is_new;
    public $is_action;
    public $is_recommend;
    public $tax_id;
    public $tax_included;
    public $quantity;
    public $quantity_from;
    public $quantity_to;
    public $discount;
    public $price;
    public $currency_id;
    public $unit_id;
    public $warranty;
    public $warranty_period_id;
    public $views;
    public $sort;
    public $created;
    public $updated;

    /**
     * Возвращает товар по id
     * @param int $id - id товара
     * @param bool $active - активность
     * @param bool $object - возвращать объект/массив
     * @return bool|mixed
     */
    public static function getById(int $id, bool $active = true, bool $object = true)
    {
        $params = [':id' => $id];
        $activity = !empty($active) ? 'AND p.active IS NOT NULL AND g.active IS NOT NULL AND v.active IS NOT NULL' : '';
        $sql = "
            SELECT 
                p.id, p.name, p.articul, p.quantity, p.quantity_from, p.quantity_to, p.discount,  p.views,
                p.is_hit, p.is_new, p.is_action, p.is_recommend,
                p.preview_image, p.preview_text, p.preview_text_type_id, ptt.name preview_text_type, 
                p.detail_image, p.detail_text, p.detail_text_type_id, dtt.name detail_text_type, 
                p.unit_id, u.name unit_name, u.sign unit, 
                p.warranty, p.warranty_period_id, wp.name warranty_name,
                p.group_id, g.name group_name, g.link group_link,
                p.vendor_id, v.name vendor, v.image vendor_image,
                p.tax_id, p.tax_included, t.name tax_name, t.value tax_value 
            FROM products p  
            LEFT JOIN `groups` g 
                ON p.group_id = g.id 
            LEFT JOIN vendors v 
                ON p.vendor_id = v.id 
            LEFT JOIN taxes t 
                ON p.tax_id = t.id 
            LEFT JOIN units u 
                ON p.unit_id = u.id 
            LEFT JOIN warranty_periods wp 
                ON p.warranty_period_id = wp.id 
            LEFT JOIN text_types ptt 
                ON p.preview_text_type_id = ptt.id 
            LEFT JOIN text_types dtt 
                ON p.detail_text_type_id = dtt.id 
            WHERE p.id = :id {$activity}";

        $db = Db::getInstance();
        $res = $db->query($sql, $params, $object ? static::class : null);
        return !empty($res) ? array_shift($res) : false;
    }

    /**
     * Возвращает список товаров
     * (по сути не нужен, т.к. getListByGroup делает тоже самое, но с учето групп товаров. сделан для совместимости с базовым getList)
     * @param string $order - поле сортировки
     * @param string $sort - порядок сортировки
     * @param bool $active - активность
     * @param bool $object - возвращать объект/массив
     * @return array|bool
     */
    public static function getList(string $order = 'sort', string $sort = 'ASC', bool $active = true, bool $object = true)
    {
        $activity = !empty($active) ? 'WHERE p.active IS NOT NULL AND g.active IS NOT NULL AND v.active IS NOT NULL' : '';
        $sql = "
            SELECT 
                p.id, p.name, p.articul, p.quantity, p.quantity_from, p.quantity_to, p.discount,  p.views,
                p.is_hit, p.is_new, p.is_action, p.is_recommend,
                p.preview_image, p.preview_text, p.preview_text_type_id, ptt.name preview_text_type, 
                p.detail_image, p.detail_text, p.detail_text_type_id, dtt.name detail_text_type, 
                p.unit_id, u.name unit_name, u.sign unit_sign, 
                p.warranty, p.warranty_period_id, wp.name warranty_name,
                p.group_id, g.name group_name, g.link group_link,
                p.vendor_id, v.name vendor, v.image vendor_image,
                p.tax_id, p.tax_included, t.name tax_name, t.value tax_value 
            FROM products p  
            LEFT JOIN `groups` g 
                ON p.group_id = g.id 
            LEFT JOIN vendors v 
                ON p.vendor_id = v.id 
            LEFT JOIN taxes t 
                ON p.tax_id = t.id 
            LEFT JOIN units u 
                ON p.unit_id = u.id 
            LEFT JOIN warranty_periods wp 
                ON p.warranty_period_id = wp.id 
            LEFT JOIN text_types ptt 
                ON p.preview_text_type_id = ptt.id 
            LEFT JOIN text_types dtt 
                ON p.detail_text_type_id = dtt.id 
            {$activity} 
            ORDER BY p.{$order}, p.created, p.id {$sort}";

        $db = Db::getInstance();
        $items = $db->query($sql, [], $object ? static::class : null);
        return $items ?: false;
    }

    /**
     * Возвращает список товаров по id группы
     * @param array $group - массив групп товаров (пустой массив - все товары)
     * @param string $order - поле сортировки
     * @param string $sort - порядок сортировки
     * @param bool $active - активность
     * @param bool $object - возвращать объект/массив
     * @return array|bool
     */
    public static function getListByGroup(
        array $group = [],
        int $page_number = null,
        int $page_count = null,
        string $order = 'sort',
        string $sort = 'ASC',
        bool $active = true,
        bool $object = true
    )
    {
        $groups = !empty($group) ? ('group_id IN (' . implode(',', $group) . ')') : '';
        $offset = !empty($page_number) && !empty($page_count) ? $page_count * ($page_number - 1) : null;
        $limit = isset($offset) && !empty($page_count) ? "LIMIT {$offset}, {$page_count}" : '';
        $activity = !empty($active) ? ((!empty($groups) ? 'AND ' : '') . 'p.active IS NOT NULL AND g.active IS NOT NULL AND v.active IS NOT NULL') : '';

        $sql = "
            SELECT 
                p.id, p.name, p.articul, p.quantity, p.quantity_from, p.quantity_to, p.discount,  p.views,
                p.is_hit, p.is_new, p.is_action, p.is_recommend,
                p.preview_image, p.preview_text, p.preview_text_type_id, ptt.name preview_text_type, 
                p.detail_image, p.detail_text, p.detail_text_type_id, dtt.name detail_text_type, 
                p.unit_id, u.name unit_name, u.sign unit, 
                p.warranty, p.warranty_period_id, wp.name warranty_name,
                p.group_id, g.name group_name, g.link group_link,
                p.vendor_id, v.name vendor, v.image vendor_image,
                p.tax_id, p.tax_included, t.name tax_name, t.value tax_value 
            FROM products p  
            LEFT JOIN `groups` g 
                ON p.group_id = g.id 
            LEFT JOIN vendors v 
                ON p.vendor_id = v.id 
            LEFT JOIN taxes t 
                ON p.tax_id = t.id 
            LEFT JOIN units u 
                ON p.unit_id = u.id 
            LEFT JOIN warranty_periods wp 
                ON p.warranty_period_id = wp.id 
            LEFT JOIN text_types ptt 
                ON p.preview_text_type_id = ptt.id 
            LEFT JOIN text_types dtt 
                ON p.detail_text_type_id = dtt.id 
            WHERE {$groups} {$activity} 
            ORDER BY p.{$order}, p.created, p.id {$sort} 
            {$limit}";

        $db = Db::getInstance();
        $items = $db->query($sql, [], $object ? static::class : null);
        return $items ?: false;
    }

    /**
     * Возвращает список товаров по id группы
     * @param array $group - массив групп товаров (пустой массив - все товары)
     * @param bool $active - активность
     * @return array|bool
     */
    public static function getCountByGroup(
        array $group = [],
        bool $active = true
    )
    {
        $groups = !empty($group) ? ('group_id IN (' . implode(',', $group) . ')') : '';
        $activity = !empty($active) ? ((!empty($groups) ? 'AND ' : '') . 'p.active IS NOT NULL AND g.active IS NOT NULL AND v.active IS NOT NULL') : '';

        $sql = "
            SELECT 
                count(p.id) count 
            FROM products p 
            LEFT JOIN `groups` g 
                ON p.group_id = g.id 
            LEFT JOIN vendors v 
                ON p.vendor_id = v.id 
            WHERE {$groups} {$activity}";

        $db = Db::getInstance();
        $res = $db->query($sql);
        return $res ? array_shift($res)['count'] : false;
    }

    /**
     * Возвращает товар с ценами
     * @param int $id - id товара
     * @param array $price_type - массив типов цен (пустой массив - все типы цен)
     * @param bool $active - активность
     * @param bool $object - возвращать объект/массив
     * @return bool|mixed
     */
    public static function getPrice(int $id, array $price_type = [2], bool $active = true, bool $object = true)
    {
        $item = self::getById($id, $active, $object);
        if (!empty($item)) {
            $item->prices = ProductPrice::getPrice($id, $price_type, $active);
            $item->images = ProductImage::getByProductId($id);
            $item->stores = ProductStore::getQuantities($id);
        }
        return $item ?: false;
    }

    /**
     * Возвращает список товаров определенной группы с ценами
     * @param array $group - массив групп товаров (пустой массив - все товары)
     * @param array $price_type - массив типов цен (пустой массив - все типы цен)
     * @param string $order - поле сортировки
     * @param string $sort - порядок сортировки
     * @param bool $active - активность
     * @param bool $object - возвращать объект/массив
     * @return array|bool|mixed
     */
    public static function getPriceList(
        array $group = [],
        array $price_type = [],
        $page_number = null,
        $page_count = null,
        string $order = 'sort',
        string $sort = 'ASC',
        bool $active = true,
        bool $object = true
    )
    {
        $items = self::getListByGroup($group, $page_number, $page_count, $order, $sort, $active, $object);
        if (!empty($items) && is_array($items)) {
            foreach ($items as $key => $item) {
                $item->prices = ProductPrice::getPrice($item->id, $price_type, $active);
            }
        }
        return $items ?: false;
    }

    /**
     * Добавляет товару просмотр
     * @param int $id - id товара
     * @return bool
     */
    public static function addProductView(int $id)
    {
        $params = [':id' => $id];
        $sql = "UPDATE products SET views = views + 1 WHERE products.id = :id";
        $db = Db::getInstance();
        return $db->execute($sql, $params);
    }
}
