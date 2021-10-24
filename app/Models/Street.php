<?php

namespace Models;

use System\Db;
use Exceptions\DbException;

class Street extends Model
{
    protected static $table = 'fias_streets';

    /**
     * Поиск улицы по строке в указанном городе
     * @param string $city
     * @param int $limit
     * @param bool $active
     * @param bool $object
     * @return array|false
     * @throws DbException
     */
    public static function getListBySearchStringAndCityId($city_id, string $street, int $limit = 10, bool $active = false, $object = true)
    {
        $where = !empty($active) ? ' AND s.active IS NOT NULL AND r.active IS NOT NULL' : '';
        $sql = "
            SELECT s.id, r.name region, c.name city, s.name, s1.shortname shortname, s2.shortname city_shortname 
            FROM fias_streets s 
            LEFT JOIN fias_regions r ON s.region_id = r.id 
            LEFT JOIN fias_cities c ON s.city_id = c.id 
            LEFT JOIN fias_shortnames s1 ON s.shortname_id = s1.id 
            LEFT JOIN fias_shortnames s2 ON c.shortname_id = s2.id 
            WHERE s.formalname LIKE CONCAT('%', :street, '%') AND s.city_id = :city_id {$where} LIMIT {$limit}";

        $params = [
            ':street' => $street,
            ':city_id' => $city_id
        ];
        $db = Db::getInstance();
        $data = $db->query($sql, $params, $object ? static::class : null);
        return $data ?? false;
    }
}
