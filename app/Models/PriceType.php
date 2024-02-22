<?php

namespace Models;

use System\Db;

class PriceType extends Model
{
    protected static $db_table = 'price_types';

    public int $id;          // id
    public $active;      // активность
    public string $name;        // название
    public $created;     // дата создания

    public static function getAll(string $order = 'created', string $sort = 'ASC', bool $active = true, bool $object = true)
    {
        $activity = !empty($active) ? 'WHERE active IS NOT NULL' : '';
        $sql = "SELECT id FROM shop.price_types {$activity} ORDER BY {$order} " . strtoupper($sort);
        $db = Db::getInstance();
        $data = $db->query($sql, [], $object ? static::class : null);

        if (!empty($data) && is_array($data)) {
            $res = [];
            foreach ($data as $item) $res[] = $item->id;
        }
        return $res ?? false;
    }
}
