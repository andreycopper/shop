<?php

namespace Models\Product;

use System\Db;
use Models\Model;

class ProductPrice extends Model
{
    protected static $table = 'product_prices';

    public int $product_id;
    public int $price_type_id;
    public string $price_type;
    public float $price;
    public string $currency;
    public ?float $discount;
    public bool $tax_included;
    public string $tax_name;
    public float $tax_value;
    public float $rate;
    public string $iso;
    public string $logo;
    public ?string $created;
    public ?string $updated;

    /**
     * Возвращает цену определенного типа по id товара
     * @param int $product_id - id товара
     * @param int $price_type_id - тип цены
     * @param bool $object - возвращать объект/массив
     * @return false|mixed
     */
    public static function getPrice(int $product_id, int $price_type_id, bool $object = true)
    {
        $params = [
            'product_id' => $product_id,
            'price_type_id' => $price_type_id,
        ];
        $sql = "
            SELECT 
                pp. product_id, pp.price_type_id, 
                pt.name price_type, 
                ROUND(pp.price * cr.rate) price,
                IF(pp.price_type_id = 2, p.discount, 0) AS discount, 
                p.tax_included, t.value tax_value, t.name tax_name, 
                c.iso, c.logo, c.sign currency,
                pp.created, pp.updated 
            FROM product_prices pp 
            LEFT JOIN shop.products p on p.id = pp.product_id
            LEFT JOIN currency_rates cr ON pp.currency_id = cr.currency_id 
            LEFT JOIN currencies c on c.id = 1 
            LEFT JOIN price_types pt on pp.price_type_id = pt.id 
            LEFT JOIN taxes t ON p.tax_id = t.id 
            WHERE product_id = :product_id AND pp.price_type_id = :price_type_id";

        $db = Db::getInstance();
        $res = $db->query($sql, $params, $object ? static::class : null);
        return $res ? array_shift($res) : false;
    }

    /**
     * Возвращает цены определенных типов по id товара
     * @param int $product_id - id товара
     * @param array $price_types - массив типов цен (пустой массив - все типы цен)
     * @param bool $object - возвращать объект/массив
     * @return false|mixed
     */
    public static function getPrices(int $product_id, array $price_types = [], bool $object = true)
    {
        $price_type = !empty($price_types) ? ('AND pp.price_type_id IN (' . implode(',', $price_types) . ')') : '';

        $params = ['product_id' => $product_id];
        $sql = "
            SELECT 
                pp. product_id, pp.price_type_id, 
                pt.name price_type, 
                ROUND(pp.price * cr.rate * (100 - COALESCE(IF(pp.price_type_id = 2, p.discount, 0), 0)) / 100) price,
                IF(pp.price_type_id = 2, p.discount, 0) AS discount, 
                p.tax_included, t.value tax_value, t.name tax_name, 
                cr.rate, 
                c.iso, c.logo, c.sign currency,
                pp.created, pp.updated 
            FROM product_prices pp 
            LEFT JOIN shop.products p on p.id = pp.product_id
            LEFT JOIN currency_rates cr ON pp.currency_id = cr.currency_id 
            LEFT JOIN currencies c on c.id = 1 
            LEFT JOIN price_types pt on pp.price_type_id = pt.id 
            LEFT JOIN taxes t ON p.tax_id = t.id 
            WHERE product_id = :product_id {$price_type}";

        $db = Db::getInstance();
        $res = $db->query($sql, $params, $object ? static::class : null);
        return $res ?: false;
    }
}
