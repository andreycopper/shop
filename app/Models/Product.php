<?php

namespace Models;

use System\Db;
use Exceptions\DbException;

class Product extends Model
{
    protected static $table = 'products';

    /**
     * Возвращает все типы цен по id товара
     * @param $id
     * @param bool $object
     * @return array|false
     * @throws DbException
     */
    public static function getPrices($id, bool $object = true)
    {
        $params = [':id' => $id];
        $sql = "
            SELECT 
                pp.price_type_id, 
                pt.name price_type, 
                ROUND(pp.price * cr.rate) price, 
                c.iso, c.logo, c.sign 
            FROM product_prices pp 
            LEFT JOIN currency_rates cr ON pp.currency_id = cr.currency_id 
            LEFT JOIN currencies c on c.id = 1 
            LEFT JOIN price_types pt on pp.price_type_id = pt.id 
            WHERE product_id = :id
            ";

        $db = new Db();
        $res = $db->query($sql, $params, $object ? static::class : null);
        return $res ?: false;
    }

    /**
     * Возвращает цену определенного типа по id товара
     * @param int $product_id
     * @param int $price_type_id
     * @param bool $object
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
                c.iso, c.logo, c.sign 
            FROM product_prices pp 
            LEFT JOIN currency_rates cr ON pp.currency_id = cr.currency_id 
            LEFT JOIN currencies c on c.id = 1 
            LEFT JOIN price_types pt on pp.price_type_id = pt.id 
            WHERE product_id = :product_id AND pp.price_type_id = :price_type_id
        ";
        $db = new Db();
        $res = $db->query($sql, $params, $object ? static::class : null);

        return !empty($res) ? array_shift($res) : false;
    }

    /**
     * Возвращает товар по id
     * @param int $id
     * @param bool $active
     * @param bool $object
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
            WHERE p.id = :id {$activity}";

        $db = new Db();
        $res = $db->query($sql, $params, $object ? static::class : null);
        return !empty($res) ? array_shift($res) : false;
    }

    /**
     * Возвращает список товаров по id группы
     * @param int $group_id
     * @param bool $active
     * @param string $order
     * @param string $sort
     * @param bool $object
     * @return array|bool
     * @throws DbException
     */
    public static function getListByGroupId(int $group_id, string $order = 'sort', string $sort = 'ASC', bool $active = true, $object = true)
    {
        $activity = !empty($active) ? 'AND p.active IS NOT NULL AND g.active IS NOT NULL AND v.active IS NOT NULL' : '';
        $params = [':group_id' => $group_id];
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
            WHERE group_id = :group_id {$activity} 
            ORDER BY p.{$order}, p.created, p.id {$sort};
        ";

        $db = new Db();
        $items = $db->query($sql, $params, $object ? static::class : null);
        return $items ?: false;
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
                t.value AS tax, t.name_rus AS tax_name,
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



    public static function getQuantity(int $id, bool $object = true)
    {
        $sql = "
            SELECT p.quantity 
            FROM products p 
            WHERE p.id = :id
        ";
        $params = [
            ':id' => $id
        ];
        $db = new Db();
        $data = $db->query($sql, $params, $object ? static::class : null);
        return !empty($data) ? array_shift($data) : false;
    }

    /**
     * Добавляет товару просмотр
     * @param int $id
     * @return bool
     * @throws DbException
     */
    public static function addProductView(int $id)
    {
        $sql = "
            UPDATE products 
            SET views = views + 1
            WHERE products.id = :id
        ";
        $params = [
            ':id' => $id
        ];
        $db = new Db();
        return $db->execute($sql, $params);
    }

    public static function getCount($id, bool $active = false)
    {
        $sql = "SELECT quantity FROM products WHERE id = :id";
        $sql .= !empty($active) ? ' AND active IS NOT NULL' : '';
        $params = [
            ':id' => $id
        ];
        $db = new Db();
        $data = $db->query($sql, $params);

        return !empty($data) ? array_shift($data)['quantity'] : false;
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
