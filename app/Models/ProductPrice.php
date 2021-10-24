<?php

namespace Models;

use System\Db;
use Exceptions\DbException;

class ProductPrice extends Model
{
    protected static $table = 'product_prices';
    public $product_id;
    public $price_type_id;
    public $price;
    public $currency_id;
    public $created;
    public $updated;

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

        $db = Db::getInstance();
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

        $db = Db::getInstance();
        $res = $db->query($sql, $params, $object ? static::class : null);
        return $res ?: false;
    }
}
