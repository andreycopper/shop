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

    public static function images()
    {
        $row = 1;

        if (($handle = fopen(__DIR__ . "/../../public/files/catalog.csv", "r")) !== FALSE) {
            $success = 0;
            $error = 0;
            $db = new Db();

            while (($data = fgetcsv($handle, 5000, ";")) !== false) {
                if($row >= 2) {
                    if (!empty($data[16])) {
                        $image = __DIR__ . "/../../public/files/" . $data[16];
                        $filename = explode('/', $data[16]);
                        $filename = array_pop($filename);

                        if (is_file($image)) {
                            $params = [
                                ':xml_id' => trim($data[1]),
                            ];
                            $sql = "select id, name from shop.products where xml_id = :xml_id";

                            $res = $db->query($sql, $params);

                            if ($res) {
                                $product_id = array_shift($res)['id'];

                                $dir = __DIR__ . '/../../public/uploads/catalog/' . $product_id;
                                if(!is_dir($dir)) mkdir($dir, 0777, true);

                                copy($image, $dir . '/' . $filename);

                                $_params = [
                                    ':product_id' => $product_id,
                                    ':image' => $filename,
                                ];
                                $sql = "insert into shop.product_images (product_id, image) values (:product_id, :image)";

                                $result = $db->iquery($sql, $_params);

                                if ($result) $success++;
                                else $error++;

                                var_dump($result);
                            }

                        }
                    }
                }

                $row++;
            }

            var_dump('Всего: ' . $row);
            var_dump('Успешно: ' . $success);
            var_dump('Ошибок: ' . $error);

            fclose($handle);
        }
    }
}
