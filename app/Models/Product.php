<?php

namespace App\Models;

use App\System\Db;
use App\Exceptions\DbException;

class Product extends Model
{
    protected static $table = 'products';

    /**
     * Находит и возвращает одну запись из БД по id
     * @param int $id
     * @param bool $active
     * @param bool $object
     * @return bool|mixed
     * @throws DbException
     */
    public static function getById(int $id, bool $active = false, $object = true)
    {
        $sql = "SELECT * FROM products WHERE id = :id";
        $sql .= !empty($active) ? ' AND active IS NOT NULL' : '';
        $params = [
            ':id' => $id
        ];
        $db = new Db();
        $data = $db->query($sql, $params, $object ? static::class : null);
        return !empty($data) ? array_shift($data) : false;
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
                   cr.rate 
            FROM products p 
            LEFT JOIN product_prices pp 
                ON p.id = pp.product_id AND pp.price_type_id = :price_type
            LEFT JOIN currency_rates cr 
                ON pp.currency_id = cr.currency_id 
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

    /**
     * Находит и возвращает цену товара
     * @param int $product_id
     * @param int $price_type
     * @param bool $object
     * @return false|mixed
     * @throws DbException
     */
    public static function getPrice(int $product_id, int $price_type, bool $object = true)
    {
        $where = !empty($active) ? ' AND p.active IS NOT NULL' : '';
        $sql = "
            SELECT pp.price,
                   p.discount,
                   c.sign AS currency,
                   cr.rate 
            FROM product_prices pp 
            LEFT JOIN products p 
                ON p.id = pp.product_id
            LEFT JOIN currencies c 
                ON pp.currency_id = c.id
            LEFT JOIN currency_rates cr 
                ON c.id = cr.currency_id
            WHERE pp.product_id = :product_id 
              AND pp.price_type_id = :price_type_id {$where}
        ";
        $params = [
            ':product_id' => $product_id,
            ':price_type_id' => $price_type,
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
     * @param int $group_id
     * @param int $price_type
     * @param bool $active
     * @param string $orderBy
     * @param string $sort
     * @param bool $object
     * @return array|bool
     * @throws DbException
     */
    public static function getListByGroup(int $group_id, int $price_type, bool $active = false, string $orderBy = 'sort', string $sort = 'ASC', $object = true)
    {
        $params = [
            ':group_id'   => $group_id,
            ':price_type'   => $price_type
        ];
        $where = !empty($active) ? ' AND p.active IS NOT NULL' : '';
        $sql = "
            SELECT 
                p.id, p.active, p.name, p.articul, p.preview_image, p.preview_text, p.detail_text, 
                p.tax_included, p.quantity, p.discount, p.warranty, 
                p.is_hit as hit, p.is_new as new, p.is_action as action, p.is_recommend as recommend, 
                pp.price,
                pt.id AS price_type_id, pt.name AS price_type,
                c.sign as currency, 
                cr.rate, 
                u.sign as unit, 
                t.value as vat, 
                v.name as vendor, 
                pd.sign as warranty_period 
            FROM products p 
            LEFT JOIN product_prices pp 
                ON p.id = pp.product_id AND pp.price_type_id = :price_type 
            LEFT JOIN price_types pt 
                ON pt.id = pp.price_type_id 
            LEFT JOIN currencies c 
                ON pp.currency_id = c.id 
            LEFT JOIN currency_rates cr 
                ON pp.currency_id = cr.currency_id 
            LEFT JOIN units u 
                ON p.unit_id = u.id 
            LEFT JOIN taxes t 
                ON p.tax_id = t.id 
            LEFT JOIN vendors v 
                ON p.vendor_id = v.id 
            LEFT JOIN periods pd 
                ON p.warranty_period_id = pd.id 
            WHERE group_id = :group_id 
            {$where} 
            ORDER BY p.{$orderBy} {$sort}, p.created DESC;
        ";

//        $sql = "
//            SELECT
//                products.id, products.active, products.name, products.articul, products.preview_image, products.preview_text, products.detail_text,
//                products.vat_included, products.quantity, products.discount, products.price, products.warranty,
//                products.is_hit as hit, products.is_new as new, products.is_action as action, products.is_recommend as recommend,
//                currencies.sign as currency,
//                currency_rates.rate,
//                units.sign as unit,
//                taxes.value as vat,
//                vendors.name as vendor,
//                periods.sign as warranty_period
//            FROM products
//            LEFT JOIN currencies ON products.currency_id = currencies.id
//            LEFT JOIN currency_rates ON products.currency_id = currency_rates.currency_id
//            LEFT JOIN units ON products.unit_id = units.id
//            LEFT JOIN taxes ON products.vat_id = taxes.id
//            LEFT JOIN vendors ON products.vendor_id = vendors.id
//            LEFT JOIN periods ON products.warranty_period_id = periods.id
//            WHERE group_id IN
//                (SELECT id FROM `groups` WHERE groups.name = :group)
//            {$where}
//            ORDER BY products.{$orderBy} {$sort}, products.created DESC;
//        ";

        $db = new Db();
        $items = $db->query($sql, $params, $object ? static::class : null);

        return $items ?? false;
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

//    /**
//     * @param $id
//     * @param bool $active
//     * @return false|float
//     * @throws DbException
//     */
//    public static function getPrice($id, bool $active = false)
//    {
//        $sql = "SELECT price, discount FROM products WHERE id = :id";
//        $sql .= !empty($active) ? ' AND active IS NOT NULL' : '';
//        $params = [
//            ':id' => $id
//        ];
//        $db = new Db();
//        $data = $db->query($sql, $params);
//
//        if (!empty($data)) {
//            $item = array_shift($data);
//            return round($item['price'] * (100 - $item['discount']) / 100);
//        }
//
//        return false;
//    }

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
