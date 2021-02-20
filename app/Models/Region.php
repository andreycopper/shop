<?php

namespace Models;

use System\Db;

class Region extends Model
{
    protected static $table = 'regions';

    public static function getListByDistrictId(int $district_id, bool $active = false, $object = true)
    {
        $where = !empty($active) ? ' WHERE active IS NOT NULL' : '';

        $sql = 'SELECT * FROM regions WHERE district_id = :district_id';
        $sql .= !empty($active) ? ' AND active IS NOT NULL' : '';
        $params = [
            ':district_id' => $district_id
        ];

        $db = new Db();
        $data = $db->query($sql, $params, $object ? static::class : null);
        return $data ?? false;
    }
}
