<?php

namespace Models\Fias;

use System\Db;
use Models\Model;
use Exceptions\DbException;

class Street extends Model
{
    protected static $db_table = 'fias.streets';

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
            FROM fias.streets s 
            LEFT JOIN fias.regions r ON s.region_id = r.id 
            LEFT JOIN fias.cities c ON s.city_id = c.id 
            LEFT JOIN fias.shortnames s1 ON s.shortname_id = s1.id 
            LEFT JOIN fias.shortnames s2 ON c.shortname_id = s2.id 
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
