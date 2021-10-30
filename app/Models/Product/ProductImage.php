<?php

namespace Models\Product;

use System\Db;
use Models\Model;

class ProductImage extends Model
{
    protected static $table = 'product_images';
    public $id;
    public $product_id;
    public $image;
    public $sort;
    public $created;
    public $updated;

    public static function getByProductId(int $product_id, bool $object = true)
    {
        $params = [
            ':product_id' => $product_id
        ];
        $sql = "
            SELECT pi.image 
            FROM product_images pi 
            WHERE pi.product_id = :product_id";

        $db = Db::getInstance();
        $res = $db->query($sql, $params, $object ? static::class : null);
        return $res ?: false;
    }
}
