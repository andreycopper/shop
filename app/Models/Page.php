<?php

namespace Models;

use Models\Product\Product;
use System\Db;
use Exceptions\DbException;

class Page extends Model
{
    protected static $table = 'pages';

    /**
     * Находит и возвращает активные записи из БД и формирует иерархическое меню
     * @param string $sort - сортировка
     * @param string $order - направление сортировки
     * @param bool $active - активность
     * @param bool $object - возвращать объект/массив
     * @return array|bool
     */
    public static function getMenuTree(string $sort = 'sort', string $order = 'ASC', bool $active = true, bool $object = false)
    {
        $activity = !empty($active) ? 'AND p.active IS NOT NULL' : '';
        $sql = "
            SELECT p.id, p.menu, p.footer, p.parent_id, p.name, p.link, p.description, p.meta_d, p.meta_k, p.sort 
            FROM pages p 
            WHERE p.menu IS NOT NULL {$activity} 
            ORDER BY {$sort} {$order}";
        $db = Db::getInstance();
        $data = $db->query($sql, [],$object ? static::class : null);
        $res = [];

        if (!empty($data)) {
            foreach ($data as $item) {
                if (empty($item['parent_id'])) $res[0][$item['id']] = $item;
                else $res[$item['parent_id']][$item['id']] = $item;
            }
        }
        if (!empty($res)) $_SESSION['menu'] = $res;
        return $res ?: false;
    }

    /**
     * @param string $sort - сортировка
     * @param string $order - направление сортировки
     * @param bool $active - активность
     * @param bool $object - возвращать объект/массив
     * @return false
     */
    public static function getProfileMenu(string $sort = 'sort', string $order = 'ASC', bool $active = true, bool $object = true)
    {
        $activity = !empty($active) ? 'AND p.active IS NOT NULL' : '';
        $sql = "
            SELECT p.id, p.parent_id, p.name, p.link, p.description, p.meta_d, p.meta_k, p.sort 
            FROM pages p 
            WHERE p.personal IS NOT NULL {$activity} 
            ORDER BY {$sort} {$order}";
        $db = Db::getInstance();
        $data = $db->query($sql, [],$object ? static::class : null);

        if (!empty($data)) $_SESSION['menu_personal'] = $data;
        return $data ?: false;
    }

    /**
     * Получает информацию по текущей странице
     * @param array $url
     * @return false|mixed
     */
    public static function getPageInfo(array $url)
    {
        if (!empty($url) && is_array($url)) {
            $last = array_pop($url);

            $page =
                !empty($url[0]['name']) && $url[0]['name'] === 'Catalog' ?

                    (is_numeric($last['name']) ?
                        Product::getById($last['name']) :
                        Group::getByField('link', mb_strtolower($last['name']))) :

                    (is_numeric($last['name']) ?
                        '' :
                        Page::getByField('link', $last['link'])
                    );
        }

        return $page ?? false;
    }

    /**
     * Возвращает "хлебные крошки"
     * @return array|false
     */
    public static function getBreadCrumbs()
    {
        if (!empty(URL) && is_array(URL)) {
            $result = [];
            $url = '';
            $url_group = '/';

            for ($i = 0; $i < count(URL); $i++) {
                $url .= ($url ? '/' : '') . mb_strtolower(URL[$i]['name']);

                $page =
                    $i > 0 && !empty(URL[0]['name']) && URL[0]['name'] === 'Catalog' ?

                        (is_numeric(URL[$i]['name']) ?
                            Product::getById(URL[$i]['name']) :
                            Group::getByField('link', mb_strtolower(URL[$i]['name']))) :

                        (is_numeric(URL[$i]['name']) ?
                            '' :
                            Page::getByField('link', $url)
                            );

                if (!empty($page)) {
                    $result[$i]['name'] = $page->name;

                    if (isset(URL[$i+1]) && !is_numeric(URL[$i]['name'])) {
                        if (!empty(URL[0]['name']) && URL[0]['name'] !== 'Catalog') {
                            $result[$i]['link'] =  '/' . $page->link . '/';
                        } else {
                            $url_group .= ($page->link . '/');
                            $result[$i]['link'] =  $url_group;
                        }
                    }
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