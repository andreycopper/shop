<?php

namespace Models;

use System\Db;
use Exceptions\DbException;

class Group extends Model
{
    protected static $table = 'groups';

    /**
     * Возвращает список подкатегорий
     * @param $group_id
     * @param bool $active
     * @param string $order
     * @param string $sort
     * @return array|bool
     * @throws DbException
     */
    public static function getSubGroups($group_id, $active = true, $order = 'sort', $sort = 'ASC')
    {
        $activity = !empty($active) ? 'AND g.active IS NOT NULL' : '';
        $params = [':group_id'   => $group_id];
        $sql = "
            SELECT g.id, g.name, g.link, g.image 
            FROM `groups` g 
            WHERE g.parent_id = :group_id 
            {$activity} 
            ORDER BY g.{$order}, g.created, g.id {$sort}";

        $db = Db::getInstance();
        $res = $db->query($sql, $params, static::class);
        return $res ?? false;
    }








































    /**
     * Находит и возвращает активные записи из БД и формирует иерархическое меню
     * @param bool $active
     * @param bool $object
     * @param string $orderBy
     * @param string $order
     * @return array|bool
     * @throws DbException
     */
    public static function getCatalog($active = true, $object = false, $orderBy = 'sort', $order = 'ASC')
    {
        $activity = !empty($active) ? 'WHERE g.active IS NOT NULL' : '';
        $sql = "
            SELECT 
                g.id, g.parent_id, g.name, g.link, g.image, g.description, 
                tt.id as description_type_id, tt.name as description_type, 
                g.sort 
            FROM `groups` g 
            LEFT JOIN text_types tt ON g.description_type_id = tt.id 
            {$activity} 
            ORDER BY g.{$orderBy} {$order}, g.created DESC
        ";

        $db = Db::getInstance();
        $data = $db->query($sql, [],$object ? static::class : null);

        if (!empty($data)) {
            $res = [];
            foreach ($data as $item) {
                $item['link'] = str_replace('_', '-', $item['link']);
                if (empty($item['parent_id'])) $res[0][$item['id']] = $item;
                else $res[$item['parent_id']][$item['id']] = $item;
            }
            $_SESSION['groups'] = $res;
        }
        return $res ?? false;
    }


    public function filter_id($id)
    {
        return (int)$id;
    }

    public function filter_active($value)
    {
        return (int)$value;
    }

    public function filter_group_id($value)
    {
        return (int)$value;
    }

    public function filter_name($text)
    {
        return strip_tags(trim($text));
    }

    public function filter_title($text)
    {
        return strip_tags(trim($text));
    }

    public function filter_description($text)
    {
        return strip_tags(trim($text), '<p><div><span><b><strong><i><br><h1><h2><h3><h4><h5><h6><ul><ol><li><a><table><tr><th><td><caption>');

    }

    public function filter_description_type_id($value)
    {
        return (int)$value;
    }

    public function filter_sort($value)
    {
        return (int)$value;
    }
}