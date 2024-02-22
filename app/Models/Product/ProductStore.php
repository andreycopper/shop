<?php
namespace Models\Product;

use System\Db;
use Models\Model;

class ProductStore extends Model
{
    protected static $db_table = 'product_stores';

    public int $product_id;
    public int $store_id;
    public int $quantity;
    public ?string $created;
    public ?string $updated;

    /**
     * Возвращает массив складов с количеством товара
     * @param int $product_id - id товара
     * @param array $params
     * @return array
     */
    public static function getStores(int $product_id, array $params = [])
    {
        $active = !empty($params['active']) ? 'AND p.active IS NOT NULL AND s.active IS NOT NULL' : '';

        $db = Db::getInstance();
        $db->params = ['product_id' => $product_id];
        $db->sql = "
            SELECT 
                ps.product_id, ps.store_id, ps.quantity, s.name, 
                CONCAT(sn.shortname, '. ', c.name, '. ', s.address) AS address,
                ps.created, ps.updated 
            FROM shop.product_stores ps 
            LEFT JOIN shop.products p on p.id = ps.product_id
            LEFT JOIN shop.stores s ON s.id = ps.store_id 
            LEFT JOIN fias.cities c ON c.id = s.city_id 
            LEFT JOIN fias.shortnames sn ON c.shortname_id = sn.id 
            WHERE product_id = :product_id {$active}";

        $res = $db->query();
        return $res ?: [];
    }
}
