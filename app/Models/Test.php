<?php

namespace Models;

use System\Db;

class Test extends Model
{
    protected static $table = 'test';

    public static function fias($object = null)
    {
        $sql = "SELECT id, shortname FROM fias_shortnames";
        $db = new Db();
        $data = $db->query($sql, []);
    }

    public static function prices()
    {
        $sql = "SELECT * FROM product_prices";
        $db = new Db();
        $data = $db->query($sql, []);

        foreach ($data as $elem) {
            $params = [
                ':product_id'    => $elem['product_id'],
                ':zakup'         => round($elem['price'] / 3 * 2),
                ':opt'           => round($elem['price'] / 10 * 9),
                ':currency_id'   => $elem['currency_id'],
            ];
            $sql = "
                INSERT INTO product_prices 
                    (product_id, price_type_id, price, currency_id) 
                    VALUES 
                           (:product_id, 1, :zakup, :currency_id),
                           (:product_id, 3, :opt, :currency_id);";

            $db = new Db();
            $res = $db->iquery($sql, $params);
            var_dump($res);

        }
    }

    public static function views()
    {
        $sql = "SELECT * FROM products";
        $db = new Db();
        $data = $db->query($sql, []);

        foreach ($data as $elem) {
            $params = [
                ':id'  => $elem['id'],
                ':num' => random_int(0, 1000),
            ];
            $sql = "UPDATE products SET views = :num WHERE id = :id";

            $db = new Db();
            $res = $db->iquery($sql, $params);
            var_dump($res);
        }
    }

    public static function quantity()
    {
        $sql = "SELECT * FROM products";
        $db = new Db();
        $data = $db->query($sql, []);

        foreach ($data as $elem) {
            $params = [
                ':id'  => $elem['id'],
                ':num' => random_int(0, 100),
            ];
            $sql = "UPDATE products SET quantity = :num WHERE id = :id";

            $db = new Db();
            $res = $db->iquery($sql, $params);
            var_dump($res);
        }
    }

    public static function actions()
    {
        for ($i = 1; $i <= 4; $i++) {
            $params = [
                ':id'  => random_int(1, 1985)
            ];
            $sql = "UPDATE products SET is_recommend = 1 WHERE id = :id";

            $db = new Db();
            $res = $db->iquery($sql, $params);
            var_dump($res);
        }
    }
}
