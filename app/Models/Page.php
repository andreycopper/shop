<?php

namespace Models;

use System\Db;
use Exceptions\DbException;

class Page extends Model
{
    protected static $table = 'pages';

    /**
     * Получает информацию по текущей странице
     * @param array $routes
     * @return false|mixed
     */
    public static function getPageInfo(array $routes)
    {
        if (!empty($routes) && is_array($routes)) {
            $url = array_pop($routes);
            $page =
                in_array('Catalog', $routes) && !is_numeric($url) ?
                    Group::getByField('link', mb_strtolower($url)) :
                    Page::getByField('link', $url);
        }

        return $page ?? false;
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
    public static function getMenuTree($active = false, $object = false, $orderBy = 'sort', $order = 'ASC')
    {
        $activity = !empty($active) ? 'AND p.active IS NOT NULL' : '';
        $sql = "
            SELECT p.id, p.menu, p.footer, p.parent_id, p.name, p.link, p.description, p.meta_d, p.meta_k, p.sort 
            FROM pages p 
            WHERE p.menu IS NOT NULL {$activity} 
            ORDER BY {$orderBy} {$order}";
        $db = new Db();
        $data = $db->query($sql, [],$object ? static::class : null);
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

    /**
     * Возвращает "хлебные крошки"
     * @return array|false
     */
    public static function getBreadCrumbs()
    {
        if (!empty(ROUTE) && is_array(ROUTE)) {
            $result = [];
            $link = '/';

            for ($i = 0; $i < count(ROUTE); $i++) {
                $page =
                    in_array('Catalog', ROUTE) && $i > 0 ?

                        (is_numeric(ROUTE[$i]) ?
                            Product::getById(ROUTE[$i], true, false) :
                            Group::getByField('link', mb_strtolower(ROUTE[$i]), true, false)) :

                        Page::getByField('link', ROUTE[$i], true, false);

                if (!empty($page)) {
                    $link .= ($page['link'] . '/');

                    $result[$i]['name'] = $page['name'];
                    if (isset(ROUTE[$i+1]) && !is_numeric(ROUTE[$i])) $result[$i]['link'] = $link;
                }
            }
        }

        return $result ?? false;
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