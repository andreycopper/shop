<?php

namespace Models\Product;

use System\Db;
use Models\Model;

class ProductStore extends Model
{
    protected static $table = 'product_stores';
    public $product_id;
    public $store_id;
    public $quantity;
    public $created;
    public $updated;

    /**
     * Возвращает количество товаров на складах
     * @param int $product_id - id товара
     * @param bool $object - возвращать объект/массив
     * @return array|false
     */
    public static function getQuantities(int $product_id, bool $object = true)
    {
        $params = [
            ':product_id' => $product_id,
        ];
        $sql = "
            SELECT ps.product_id, ps.store_id, ps.quantity, s.name, CONCAT(sn.shortname, '. ', c.name, '. ', s.address) address 
            FROM product_stores ps 
            LEFT JOIN shop.stores s ON s.id = ps.store_id 
            LEFT JOIN fias.cities c ON c.id = s.city_id 
            LEFT JOIN fias.shortnames sn ON c.shortname_id = sn.id 
            WHERE product_id = :product_id";

        $db = Db::getInstance();
        $res = $db->query($sql, $params, $object ? static::class : null);
        return $res ?: false;
    }
}
