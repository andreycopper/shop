<?php

namespace Models;

use System\Db;

class Group extends Model
{
    protected static $table = 'groups';

    public $id;
    public $active;
    public $parent_id;
    public $link;
    public $name;
    public $image;
    public $description;
    public $description_type_id;
    public $sort;
    public $created;
    public $updated;

    /**
     * Находит и возвращает активные записи с количеством товаров из БД и формирует иерархическое меню
     * @param bool $active - возвращать активные/неактивные записи
     * @param bool $object - возвращать объект/массив
     * @param string $orderBy - сортировка
     * @param string $order - направление сортировки
     * @return array|bool
     */
    public static function getCatalog(bool $active = true, bool $object = true, string $orderBy = 'sort', string $order = 'ASC')
    {
        $activity = !empty($active) ? 'WHERE g.active IS NOT NULL' : '';
        $sql = "
            SELECT 
                g.id, g.parent_id, g.name, g.link, g.image, g.description, 
                tt.id as description_type_id, tt.name as description_type, 
                g.sort, count(p.id) count 
            FROM `groups` g 
            LEFT JOIN text_types tt ON g.description_type_id = tt.id 
            LEFT JOIN shop.products p on g.id = p.group_id 
            {$activity} 
            GROUP BY g.id 
            ORDER BY g.{$orderBy} {$order}, g.created DESC
        ";

        $db = Db::getInstance();
        $data = $db->query($sql, [],$object ? static::class : null);

        if (!empty($data)) {
            $res = [];
            foreach ($data as $item) {
                $item->link = str_replace('_', '-', $item->link);
                if (empty($item->parent_id)) $res[0][$item->id] = $item;
                else $res[$item->parent_id][$item->id] = $item;
            }
            $_SESSION['groups'] = $res;
        }
        return $res ?? false;
    }

    /**
     * Возвращает список подкатегорий
     * @param int $group_id
     * @param bool $active
     * @param string $order
     * @param string $sort
     * @return array|bool
     */
    public static function getSubGroups(int $group_id, bool $active = true, string $order = 'sort', string $sort = 'ASC')
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