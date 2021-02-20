<?php

namespace Models;

use System\Db;

class Group extends Model
{
    protected static $table = 'groups';

    /**
     * Находит и возвращает активные записи из БД и формирует иерархическое меню
     * @param bool $active
     * @param string $orderBy
     * @param string $order
     * @return array|bool
     * @throws \App\Exceptions\DbException
     */
    public static function getCatalog($active = false, $orderBy = 'sort', $order = 'ASC')
    {
        $where = !empty($active) ? 'WHERE items.active IS NOT NULL' : '';
        $sql = "
            SELECT g.id, g.parent_id, g.name, g.title, g.image, g.description, text_types.name as description_type, g.sort 
            FROM `groups` g 
            LEFT JOIN text_types ON g.description_type_id = text_types.id 
            {$where} 
            ORDER BY g.{$orderBy} {$order}, g.created DESC
        ";

        $db = new Db();
        $data = $db->query($sql);

        if (!empty($data)) {
            $res = [];
            foreach ($data as $item) {
                if (empty($item['parent_id'])) $res[0][$item['id']] = $item;
                else $res[$item['parent_id']][$item['id']] = $item;
            }
            $_SESSION['groups'] = $res;
        }
        return $res ?? false;
    }

    /**
     * Находит и возвращает список подкатегорий
     * @param $group_id
     * @param bool $active
     * @param string $orderBy
     * @param string $order
     * @return array|bool
     * @throws \App\Exceptions\DbException
     */
    public static function getSubGroups($group_id, $active = false, $orderBy = 'sort', $order = 'ASC')
    {
        $where = !empty($active) ? 'AND groups.active IS NOT NULL' : '';
        $params = [
            ':group_id'   => $group_id
        ];
        $sql = "
            SELECT groups.id, groups.name, groups.title, groups.image 
            FROM `groups` 
            WHERE groups.parent_id = :group_id 
            {$where} 
            ORDER BY {$orderBy} {$order}, created DESC
            ";

        $db = new Db();
        $res = $db->query($sql, $params, static::class);
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