<?php

namespace App\Models;

use App\System\Db;

class Page extends Model
{
    protected static $table = 'pages';

    /**
     * Получает информацию по текущей странице
     * @param string $page
     * @return bool|mixed
     * @throws \App\Exceptions\DbException
     */
    public static function getPageInfo(string $page)
    {
        $sql = "SELECT * FROM `pages` WHERE `name` = :page";
        $sql .= !empty($active) ? ' AND active IS NOT NULL' : '';
        $params = [
            ':page' => $page
        ];
        $db = new Db();
        $data = $db->query($sql, $params);

        if (!empty($data) && is_array($data)) {
            $res = array_shift($data);
            $_SESSION['page'] = $res;
        }
        return $res ?? false;
    }

    /**
     * Находит и возвращает активные записи из БД и формирует иерархическое меню
     * @param $page
     * @param bool $active
     * @param string $orderBy
     * @param string $order
     * @return array|bool
     * @throws \App\Exceptions\DbException
     */
    public static function getMenuTree($active = false, $orderBy = 'sort', $order = 'ASC')
    {
        $where = !empty($active) ? 'AND active IS NOT NULL' : '';
        $sql = "
            SELECT id, menu, footer, parent_id, name, link, description, meta_d, meta_k, sort 
            FROM `pages` 
            WHERE menu IS NOT NULL {$where} 
            ORDER BY {$orderBy} {$order}";
        $db = new Db();
        $data = $db->query($sql);
        $res = [];

        if (!empty($data)) {
            foreach ($data as $item) {
                if (empty($item['parent_id'])) $res[0][$item['id']] = $item;
                else $res[$item['parent_id']][$item['id']] = $item;
            }
        }
        if (!empty($res)) $_SESSION['menu'] = $res;
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

    public function filter_menu($value)
    {
        return (int)$value;
    }

    public function filter_footer($value)
    {
        return (int)$value;
    }

    public function filter_parent_id($value)
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

    public function filter_meta_d($text)
    {
        return strip_tags(trim($text));
    }

    public function filter_meta_k($text)
    {
        return strip_tags(trim($text));
    }

    public function filter_sort($value)
    {
        return (int)$value;
    }
}