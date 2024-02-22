<?php

namespace Models\Fias;

use System\Db;
use Models\Model;
use Exceptions\DbException;

class City extends Model
{
    protected static $table = 'fias.cities';
    public int $id;
    public ?bool $active;
    public $region_id;
    public string $region;
    public $aoid;
    public $aoguid;
    public $parentguid;
    public string $name;
    public $formalname;
    public $shortname_id;
    public $postalcode;
    public $citycode;
    public $areacode;
    public $ifnsfl;
    public $ifnsul;
    public $terrifnsfl;
    public $terrifnsul;
    public $okato;
    public $oktmo;
    public $sort;
    public $created;

    /**
     * Получает информацию о городе по имени
     * @param string $city
     * @param bool $active
     * @param bool $object
     * @return false|mixed
     * @throws DbException
     */
    public static function getCityLocationByName(string $city, bool $active = true, bool $object = true)
    {
        $activity = !empty($active) ? ' AND c.active IS NOT NULL AND r.active IS NOT NULL' : '';
        $sql = "
            SELECT c.id, c.active, c.name, r.name AS region, s.shortname 
            FROM fias.cities c 
            LEFT JOIN fias.regions r 
                ON c.region_id = r.id 
            LEFT JOIN fias.shortnames s 
                ON c.shortname_id = s.id 
            WHERE c.name = :city {$activity} 
            UNION ALL 
            SELECT c.id, c.active, c.name, r.name AS region, s.shortname 
            FROM fias.cities c 
            LEFT JOIN fias.regions r 
                ON c.region_id = r.id 
            LEFT JOIN fias.shortnames s 
                ON c.shortname_id = s.id
            WHERE NOT EXISTS(SELECT * FROM fias.cities WHERE name = :city) AND c.name = 'Москва' {$activity}";
        $params = [
            ':city' => $city
        ];
        $db = Db::getInstance();
        $data = $db->query($sql, $params, $object ? static::class : null);
        return !empty($data) ? array_shift($data) : false;
    }
















    public static function getById(int $id, bool $active = false, bool $object = true)
    {
        $where = !empty($active) ? ' AND c.active IS NOT NULL' : '';
        $sql = "
            SELECT c.id, c.name AS city, c.lat, c.lng, 
                   r.name AS region 
            FROM cities c 
            LEFT JOIN regions r 
                ON c.region_id = r.id 
            WHERE c.id = :id {$where} 
            UNION ALL 
            SELECT c.id, c.name AS city, c.lat, c.lng, 
                   r.name AS region 
            FROM cities c 
            LEFT JOIN regions r 
                ON c.region_id = r.id 
            WHERE NOT EXISTS(SELECT * FROM cities WHERE id = :id) AND c.id = 2387 {$where}";
        $params = [
            ':id' => $id
        ];
        $db = Db::getInstance();
        $data = $db->query($sql, $params, $object ? static::class : null);
        return !empty($data) ? array_shift($data) : false;
    }



    public static function getListByRegionId(int $region_id, bool $active = false, $object = true)
    {
        $where = !empty($active) ? ' AND active IS NOT NULL' : '';
        $sql = "SELECT * FROM cities WHERE region_id = :region_id {$where}";
        $params = [
            ':region_id' => $region_id
        ];
        $db = Db::getInstance();
        $data = $db->query($sql, $params, $object ? static::class : null);
        return $data ?? false;
    }

    /**
     * Поиск города по строке
     * @param string $city
     * @param int $limit
     * @param bool $active
     * @param bool $object
     * @return array|false
     * @throws DbException
     */
    public static function getListBySearchString(string $city, int $limit = 10, bool $active = false, $object = true)
    {
        $where = !empty($active) ? ' AND c.active IS NOT NULL AND r.active IS NOT NULL' : '';
        $sql = "
            SELECT c.id, r.name region, c.name, s1.shortname shortname 
            FROM fias.cities c 
            LEFT JOIN fias.regions r ON c.region_id = r.id 
            LEFT JOIN fias.shortnames s1 ON c.shortname_id = s1.id 
            WHERE c.formalname LIKE CONCAT('%', :city, '%') {$where} LIMIT {$limit}";

        $params = [
            ':city' => $city
        ];
        $db = Db::getInstance();
        $data = $db->query($sql, $params, $object ? static::class : null);
        return $data ?? false;
    }
}