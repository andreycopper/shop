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
            $zakup = round($elem['price'] / 3 * 2);
            $opt = round($elem['price'] / 10 * 9);

            $params = [
                ':product_id'    => $elem['product_id'],
                ':zakup'         => $zakup,
                ':opt'           => $opt,
                ':currency_id'   => $elem['currency_id'],
            ];
            $sql = "
                INSERT INTO product_prices 
                    (product_id, price_type_id, price, currency_id) 
                    VALUES 
                           (:product_id, 1, :zakup, :currency_id),
                           (:product_id, 3, :opt, :currency_id);";

            $db = new Db();
            $res = $db->query($sql, $params);
            var_dump($res);

        }
    }
}
