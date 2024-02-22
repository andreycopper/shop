<?php
namespace Models\Product;

use System\Db;
use Models\Model;

class ProductPrice extends Model
{
    protected static $db_table = 'shop.product_prices';

    public int $product_id;
    public int $price_type_id;
    public float $price;
    public string $currency_id;
    public string $created;
    public ?string $updated = null;

    /**
     * Возвращает цену определенного типа по id товара
     * @param int $product_id - id товара
     * @param int $price_type_id - тип цены
     * @param array $params
     * @return array|null
     */
    public static function getPrice(int $product_id, int $price_type_id, array $params = [])
    {
        $params += ['active' => true, 'object' => false];
        $active = !empty($params['active']) ? 'AND p.active IS NOT NULL' : '';

        $db = Db::getInstance();
        $db->params = ['product_id' => $product_id, 'price_type_id' => $price_type_id];
        $db->sql = "
            SELECT 
                pp. product_id, pp.price_type_id, pt.name AS price_type, 
                pp.price * cr.rate AS price, 
                pp.price * cr.rate * (100 - COALESCE(IF(pp.price_type_id = 2, p.discount, 0), 0)) / 100 AS price_discount, 
                IF(pp.price_type_id = 2, p.discount, 0) AS discount, 
                pp.currency_id, c.iso AS currency, c.logo AS currency_logo, c.sign AS currency_sign, cr.rate AS currency_rate, 
                p.tax_id, p.tax_included, t.name AS tax, t.value AS tax_value, 
                pp.created, pp.updated 
            FROM product_prices pp 
            LEFT JOIN shop.products p on p.id = pp.product_id
            LEFT JOIN currency_rates cr ON pp.currency_id = cr.currency_id 
            LEFT JOIN currencies c on c.id = 1 
            LEFT JOIN price_types pt on pp.price_type_id = pt.id 
            LEFT JOIN taxes t ON p.tax_id = t.id 
            WHERE product_id = :product_id AND pp.price_type_id = :price_type_id {$active}";

        $res = $db->query();
        return $res ?: null;
    }

    /**
     * Возвращает цены определенных типов по id товара
     * @param int $product_id - id товара
     * @param array $price_types - массив типов цен (пустой массив - все типы цен)
     * @param array $params
     * @return array|null
     */
    public static function getPrices(int $product_id, array $price_types = [], array $params = [])
    {
        $params += ['active' => true, 'object' => false];
        $active = !empty($params['active']) ? 'AND p.active IS NOT NULL' : '';
        $price_type = !empty($price_types) ? ('AND pp.price_type_id IN (' . implode(',', $price_types) . ')') : '';

        $db = Db::getInstance();
        $db->params = ['product_id' => $product_id];
        $db->sql = "
            SELECT 
                pp. product_id, pp.price_type_id, pt.name AS price_type, 
                pp.price * cr.rate AS price, 
                pp.price * cr.rate * (100 - COALESCE(IF(pp.price_type_id = 2, p.discount, 0), 0)) / 100 AS price_discount, 
                IF(pp.price_type_id = 2, p.discount, 0) AS discount, 
                pp.currency_id, c.iso AS currency, c.logo AS currency_logo, c.sign AS currency_sign, cr.rate AS currency_rate, 
                p.tax_id, p.tax_included, t.name AS tax, t.value AS tax_value, 
                pp.created, pp.updated 
            FROM shop.product_prices pp 
            LEFT JOIN shop.products p on p.id = pp.product_id
            LEFT JOIN shop.currency_rates cr ON pp.currency_id = cr.currency_id 
            LEFT JOIN shop.currencies c on c.id = 1 
            LEFT JOIN shop.price_types pt on pp.price_type_id = pt.id 
            LEFT JOIN shop.taxes t ON p.tax_id = t.id 
            WHERE product_id = :product_id {$price_type} {$active}";

        $res = $db->query();
        return $res ?: null;
    }
}
