<?php

namespace Models;

use System\Db;
use System\Cache;

class Group extends Model
{
    protected static $table = 'groups';

    public int $id;
    public ?bool $active;
    public ?int $parent_id;
    public string $link;
    public string $name;
    public ?string $image;
    public ?string $description;
    public int $description_type_id;
    public string $description_type;
    public ?int $count;
    public int $sort;
    public ?string $created;
    public ?string $updated;

    /**
     * Возвращает категорию товаров (+)
     * @param string $name - название категории
     * @return array|false|object
     */
    public static function get(string $name)
    {
        if ($data = Cache::getGroup($name)) return $data;
        return self::getByField('link', $name);
    }

    /**
     * Возвращает список подкатегорий (+)
     * @param int $group_id - id родительской категории
     * @return array
     */
    public static function getSubGroups(int $group_id)
    {
        if ($data = Cache::getSubGroups($group_id)) return $data;
        return self::getListSubGroups($group_id);
    }

    /**
     * Получает главное меню из сессии, кэша и БД по порядку в случае отсутствия (+)
     * @return array|bool|mixed
     */
    public static function getCatalogMenu()
    {
        if (!empty($_SESSION['menu']['groups']) &&
            !empty($_SESSION['menu']['groups_expiration']) &&
            $_SESSION['menu']['groups_expiration'] > time())
        {
            return $_SESSION['menu']['groups'];
        }
        elseif ($data = Cache::getMenu('groups')) return $data;

        $time = time();
        $time += 60 * 60 * 24;
        $data = self::getCatalog();

        if (!empty($data)) {
            $_SESSION['menu']['groups_expiration'] = $time;
            $_SESSION['menu']['groups'] = $data;
        }
        return $data;
    }

    /**
     * Находит и возвращает активные записи с количеством товаров из БД и формирует иерархическое меню
     * @param string $order - сортировка
     * @param string $sort - направление сортировки
     * @param bool $active - возвращать активные/неактивные записи
     * @param bool $object - возвращать объект/массив
     * @return array|bool
     */
    public static function getCatalog(string $order = 'created', string $sort = 'ASC', bool $active = true, bool $object = true)
    {
        $data = self::getList();

        if (!empty($data) && is_array($data)) {
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

    public static function getByName(string $name, string $order = 'created', string $sort = 'ASC', bool $active = true, bool $object = true)
    {
        $activity = !empty($active) ? 'AND g.active IS NOT NULL' : '';
        $params = ['name'   => $name];
        $sql = "
            SELECT 
                g.id, g.active, g.parent_id, g.name, g.link, g.image, g.description, 
                g.description_type_id, tt.name as description_type, 
                g.sort, count(p.id) count, g.created, g.updated 
            FROM `groups` g 
            LEFT JOIN text_types tt ON g.description_type_id = tt.id 
            LEFT JOIN shop.products p on g.id = p.group_id 
            WHERE g.link = :name {$activity} 
            GROUP BY g.id 
            ORDER BY g.{$order} {$sort}, g.created DESC
        ";

        $db = Db::getInstance();
        $res = $db->query($sql, $params,$object ? static::class : null);
        return $res ? array_shift($res) : false;
    }

    public static function getList(string $order = 'created', string $sort = 'ASC', bool $active = true, bool $object = true)
    {
        $activity = !empty($active) ? 'WHERE g.active IS NOT NULL' : '';
        $sql = "
            SELECT 
                g.id, g.active, g.parent_id, g.name, g.link, g.image, g.description, 
                g.description_type_id, tt.name as description_type, 
                g.sort, count(p.id) count, g.created, g.updated 
            FROM `groups` g 
            LEFT JOIN text_types tt ON g.description_type_id = tt.id 
            LEFT JOIN shop.products p on g.id = p.group_id 
            {$activity} 
            GROUP BY g.id 
            ORDER BY g.{$order} {$sort}, g.created DESC
        ";

        $db = Db::getInstance();
        return $db->query($sql, [],$object ? static::class : null);
    }

    public static function getListSubGroups($parent_id, string $order = 'created', string $sort = 'ASC', bool $active = true, bool $object = true)
    {
        $activity = !empty($active) ? 'AND g.active IS NOT NULL' : '';
        $params = ['parent_id'   => $parent_id];
        $sql = "
            SELECT 
                g.id, g.active, g.parent_id, g.name, g.link, g.image, g.description, 
                g.description_type_id, tt.name as description_type, 
                g.sort, count(p.id) count, g.created, g.updated 
            FROM `groups` g 
            LEFT JOIN text_types tt ON g.description_type_id = tt.id 
            LEFT JOIN shop.products p on g.id = p.group_id 
            WHERE g.parent_id = :parent_id {$activity} 
            GROUP BY g.id 
            ORDER BY g.{$order} {$sort}, g.created DESC
        ";

        $db = Db::getInstance();
        return $db->query($sql, $params,$object ? static::class : null);
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