<?php
namespace Models\Product;

use System\Db;
use Models\Model;

class ProductImage extends Model
{
    protected static $db_table = 'shop.product_images';

    public $id;
    public $product_id;
    public $image;
    public $sort;
    public $created;
    public $updated;

    /**
     * Возвращает массив изображений товара
     * @param int $product_id - id товара
     * @param array $params
     * @return array
     */
    public static function getImages(int $product_id, array $params = [])
    {
        $active = !empty($params['active']) ? 'AND p.active IS NOT NULL' : '';

        $db = Db::getInstance();
        $db->params = ['product_id' => $product_id];
        $db->sql = "
            SELECT 
                pi.id, pi.product_id, pi.image, pi.sort, pi.created, pi.updated
            FROM product_images pi 
            LEFT JOIN products p on pi.product_id = p.id
            WHERE pi.product_id = :product_id {$active} 
            ORDER BY pi.sort";

        $res = $db->query();
        return $res ?: [];
    }
}
