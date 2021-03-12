<?php

namespace Models;

use System\Db;
use Exceptions\DbException;

class Product extends Model
{
    protected static $table = 'products';

    /**
     * Возвращает список товаров определенной группы с ценой
     * @param array $group - массив групп товаров (пустой массив - все товары)
     * @param int $price_type - тип цены
     * @param string $order - поле сортировки
     * @param string $sort - порядок сортировки
     * @param bool $active - активность
     * @param bool $object - возвращать объект/массив
     * @return array|bool|mixed
     * @throws DbException
     */
    public static function getPriceList(array $group = [], int $price_type = 2, string $order = 'sort', string $sort = 'ASC', bool $active = true, bool $object = true)
    {
        $items = self::getListByGroup($group, $order, $sort, $active, $object);
        if (!empty($items) && is_array($items)) {
            foreach ($items as $key => $item) {
                $item->price = self::getPrice($item->id, $price_type, $active);
            }
        }
        return $items ?: false;
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
     * @throws DbException
     */
    public static function getPricesList(array $group = [], array $price_type = [], string $order = 'sort', string $sort = 'ASC', bool $active = true, bool $object = true)
    {
        $items = self::getListByGroup($group, $order, $sort, $active, $object);
        if (!empty($items) && is_array($items)) {
            foreach ($items as $key => $item) {
                $item->prices = self::getPrices($item->id, $price_type, $active);
            }
        }
        return $items ?: false;
    }

    /**
     * Возвращает товар с ценой
     * @param int $id - id товара
     * @param int $price_type - тип цены
     * @param bool $active - активность
     * @param bool $object - возвращать объект/массив
     * @return bool|mixed
     * @throws DbException
     */
    public static function getPriceItem(int $id, int $price_type = 2, bool $active = true, bool $object = true)
    {
        $item = self::getById($id, $active, $object);
        if (!empty($item)) $item->price = self::getPrice($id, $price_type, $active);
        return $item ?: false;
    }

    /**
     * Возвращает товар с ценами
     * @param int $id - id товара
     * @param array $price_type - массив типов цен (пустой массив - все типы цен)
     * @param bool $active - активность
     * @param bool $object - возвращать объект/массив
     * @return bool|mixed
     * @throws DbException
     */
    public static function getPricesItem(int $id, array $price_type = [], bool $active = true, bool $object = true)
    {
        $item = self::getById($id, $active, $object);
        if (!empty($item)) $item->prices = self::getPrices($id, $price_type, $active);
        return $item ?: false;
    }

    /**
     * Возвращает цену определенного типа по id товара
     * @param int $product_id - id товара
     * @param int $price_type_id - тип цены
     * @param bool $object - возвращать объект/массив
     * @return false|mixed
     * @throws DbException
     */
    public static function getPrice(int $product_id, int $price_type_id, bool $object = true)
    {
        $params = [
            ':product_id' => $product_id,
            ':price_type_id' => $price_type_id
        ];
        $sql = "
            SELECT 
                pp.price_type_id, 
                pt.name price_type, 
                ROUND(pp.price * cr.rate) price, 
                cr.rate, 
                c.iso, c.logo, c.sign currency 
            FROM product_prices pp 
            LEFT JOIN currency_rates cr ON pp.currency_id = cr.currency_id 
            LEFT JOIN currencies c ON c.id = 1 
            LEFT JOIN price_types pt ON pp.price_type_id = pt.id 
            WHERE product_id = :product_id AND pp.price_type_id = :price_type_id";

        $db = new Db();
        $res = $db->query($sql, $params, $object ? static::class : null);
        return $res ? array_shift($res) : false;
    }

    /**
     * Возвращает цены определенного типа по id товара
     * @param int $product_id - id товара
     * @param array $price_type - массив типов цен (пустой массив - все типы цен)
     * @param bool $object - возвращать объект/массив
     * @return false|mixed
     * @throws DbException
     */
    public static function getPrices(int $product_id, array $price_type = [], bool $object = true)
    {
        $price_types = !empty($price_type) ? ('AND pp.price_type_id IN (' . implode(',', $price_type) . ')') : '';

        $params = [':product_id' => $product_id];
        $sql = "
            SELECT 
                pp.price_type_id, 
                pt.name price_type, 
                ROUND(pp.price * cr.rate) price, 
                cr.rate, 
                c.iso, c.logo, c.sign currency 
            FROM product_prices pp 
            LEFT JOIN currency_rates cr ON pp.currency_id = cr.currency_id 
            LEFT JOIN currencies c on c.id = 1 
            LEFT JOIN price_types pt on pp.price_type_id = pt.id 
            WHERE product_id = :product_id {$price_types}";

        $db = new Db();
        $res = $db->query($sql, $params, $object ? static::class : null);
        return $res ?: false;
    }

    /**
     * Возвращает товар по id
     * @param int $id - id товара
     * @param bool $active - активность
     * @param bool $object - возвращать объект/массив
     * @return bool|mixed
     * @throws DbException
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

        $db = new Db();
        $res = $db->query($sql, $params, $object ? static::class : null);
        return !empty($res) ? array_shift($res) : false;
    }

    /**
     * Возвращает список товаров по id группы
     * @param array $group - массив групп товаров (пустой массив - все товары)
     * @param string $order - поле сортировки
     * @param string $sort - порядок сортировки
     * @param bool $active - активность
     * @param bool $object - возвращать объект/массив
     * @return array|bool
     * @throws DbException
     */
    public static function getListByGroup(array $group = [], string $order = 'sort', string $sort = 'ASC', bool $active = true, $object = true)
    {
        $groups = !empty($group) ? ('group_id IN (' . implode(',', $group) . ')') : '';
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
            ORDER BY p.{$order}, p.created, p.id {$sort}";

        $db = new Db();
        $items = $db->query($sql, [], $object ? static::class : null);
        return $items ?: false;
    }

    /**
     * Возвращает список товаров
     * @param string $order - поле сортировки
     * @param string $sort - порядок сортировки
     * @param bool $active - активность
     * @param bool $object - возвращать объект/массив
     * @return array|bool
     * @throws DbException
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

        $db = new Db();
        $items = $db->query($sql, [], $object ? static::class : null);
        return $items ?: false;
    }

    /**
     * Добавляет товару просмотр
     * @param int $id - id товара
     * @return bool
     * @throws DbException
     */
    public static function addProductView(int $id)
    {
        $params = [':id' => $id];
        $sql = "UPDATE products SET views = views + 1 WHERE products.id = :id";
        $db = new Db();
        return $db->execute($sql, $params);
    }

    /**
     * Возвращает количество товара на складе
     * @param int $id - id товара
     * @param bool $active - активность
     * @param bool $object - возвращать объект/массив
     * @return false|mixed
     * @throws DbException
     */
    public static function getQuantity(int $id, bool $active = true, bool $object = true)
    {
        $params = [':id' => $id];
        $activity = !empty($active) ? 'AND p.active IS NOT NULL' : '';
        $sql = "SELECT p.quantity FROM products p WHERE p.id = :id {$activity}";
        $db = new Db();
        $data = $db->query($sql, $params, $object ? static::class : null);
        return !empty($data) ? array_shift($data)['quantity'] : false;
    }
























































    /**
     * Находит и возвращает одну запись из БД по id
     * @param int $id
     * @param int $price_type
     * @param bool $active
     * @param bool $object
     * @return bool|mixed
     * @throws DbException
     */
    public static function getByIdWithRate(int $id, int $price_type, bool $active = false, $object = true)
    {
        $where = !empty($active) ? ' AND p.active IS NOT NULL' : '';
        $sql = "
            SELECT p.id, p.name, p.quantity, p.discount,  
                   pp.price, 
                   cr.rate, 
                   t.value tax 
            FROM products p 
            LEFT JOIN product_prices pp 
                ON p.id = pp.product_id AND pp.price_type_id = :price_type
            LEFT JOIN currency_rates cr 
                ON pp.currency_id = cr.currency_id 
            LEFT JOIN taxes t 
                ON p.tax_id = t.id
            WHERE p.id = :id {$where}
        ";
        $params = [
            ':id' => $id,
            ':price_type' => $price_type
        ];
        $db = new Db();
        $data = $db->query($sql, $params, $object ? static::class : null);
        return !empty($data) ? array_shift($data) : false;
    }

    /**
     * Находит и возвращает одну запись из БД по id с полной информацией о товаре
     * @param int $id
     * @param $price_type
     * @param bool $active
     * @param bool $object
     * @return bool|mixed
     * @throws DbException
     */
    public static function getFullInfoById(int $id, int $price_type, bool $active = false, $object = true)
    {
        $sql = "
            SELECT 
                p.id, p.name, p.articul, p.discount, p.quantity, p.detail_image, p.preview_text, p.detail_text, 
                p.is_hit AS hit, p.is_new AS new, p.is_action AS action, p.is_recommend AS recommend, 
                g.name AS group_name, g.title AS group_title, 
                v.name AS vendor, v.image AS vendor_image, 
                c.sign AS currency, 
                cr.rate,
                t.value AS tax, t.name AS tax_name,
                u.sign AS unit,
                wp.name AS warranty, 
                ptt.name AS preview_text_type, 
                dtt.name AS detail_text_type,
                pp.price 
            FROM products p 
            LEFT JOIN product_prices pp 
                ON p.id = pp.product_id AND pp.price_type_id = :price_type
            LEFT JOIN `groups` g 
                ON p.group_id = g.id 
            LEFT JOIN vendors v 
                ON p.vendor_id = v.id 
            LEFT JOIN currencies c 
                ON p.currency_id = c.id 
            LEFT JOIN currency_rates cr 
                ON p.currency_id = cr.currency_id 
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
            WHERE p.id = :id
        ";
        $sql .= !empty($active) ? ' AND p.active IS NOT NULL' : '';
        $params = [
            ':id' => $id,
            ':price_type' => $price_type
        ];
        $db = new Db();
        $data = $db->query($sql, $params, $object ? static::class : null);
        return !empty($data) ? array_shift($data) : false;
    }

    public function filter_id($id)
    {
        return (int)$id;
    }

    public function filter_xml_id($id)
    {
        return (int)$id;
    }

    public function filter_ie_id($id)
    {
        return (int)$id;
    }

    public function filter_name($text)
    {
        return strip_tags(trim($text));
    }

    public function filter_active($value)
    {
        return (int)$value;
    }

    public function filter_articul($text)
    {
        return strip_tags(trim($text));
    }

    public function filter_group_id($value)
    {
        return (int)$value;
    }

    public function filter_vendor_id($value)
    {
        return (int)$value;
    }

    public function filter_preview_image($text)
    {
        return strip_tags(trim($text));
    }

    public function filter_preview_text($text)
    {
        return trim($text);
    }

    public function filter_preview_text_type_id($value)
    {
        return (int)$value;
    }

    public function filter_detail_image($text)
    {
        return strip_tags(trim($text));
    }

    public function filter_detail_text($text)
    {
        return trim($text);
    }

    public function filter_detail_text_type_id($value)
    {
        return (int)$value;
    }

    public function filter_is_hit($value)
    {
        return (int)$value;
    }

    public function filter_is_new($value)
    {
        return (int)$value;
    }

    public function filter_is_action($value)
    {
        return (int)$value;
    }

    public function filter_is_recommend($value)
    {
        return (int)$value;
    }

    public function filter_tax_id($value)
    {
        return (float)$value;
    }

    public function filter_tax_included($value)
    {
        return (int)$value;
    }

    public function filter_quantity($value)
    {
        return (int)$value;
    }

    public function filter_quantity_from($value)
    {
        return (int)$value;
    }

    public function filter_quantity_to($value)
    {
        return (int)$value;
    }

    public function filter_discount($value)
    {
        return (float)$value;
    }

    public function filter_price($value)
    {
        return (float)$value;
    }

    public function filter_currency_id($value)
    {
        return (int)$value;
    }

    public function filter_unit_id($value)
    {
        return (int)$value;
    }

    public function filter_warranty($value)
    {
        return (int)$value;
    }

    public function filter_warranty_period_id($value)
    {
        return (int)$value;
    }

    public function filter_views($value)
    {
        return (int)$value;
    }

    public function filter_sort($value)
    {
        return (int)$value;
    }
}
