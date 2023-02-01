<?php

namespace Models;

use System\Db;
use System\Cache;
use Models\Product\Product;

class Page extends Model
{
    protected static $table = 'pages';

    public ?int $id;
    public ?bool $active;
    public ?bool $menu;
    public ?bool $personal;
    public ?bool $footer;
    public ?int $parent_id;
    public ?string $link;
    public string $name;
    public ?string $description;
    public ?string $meta_d;
    public ?string $meta_k;
    public ?int $sort;
    public ?string $created;
    public ?string $updated;

    /**
     * Получает меню из сессии, кэша и БД по порядку в случае отсутствия (+)
     * @return array|bool|mixed
     */
    public static function getMenu($type)
    {
        if (!empty($_SESSION['menu'][$type]) &&
            !empty($_SESSION['menu']["{$type}_expiration"]) &&
            $_SESSION['menu']["{$type}_expiration"] > time())
        {
            return $_SESSION['menu'][$type];
        }
        elseif ($data = Cache::getMenu($type)) return $data;

        $time = time();
        $time += 60 * 60 * 24;
        switch ($type) {
            case 'main':
                $data = self::getMainMenu();
                break;
            case 'personal':
                $data = self::getPersonalMenu();
                break;
            default:
                $data = null;
        }

        if (!empty($data)) {
            $_SESSION['menu']["{$type}_expiration"] = $time;
            $_SESSION['menu'][$type] = $data;
        }
        return $data;
    }

    /**
     * Возвращает главное меню (+)
     * @param string $sort - сортировка
     * @param string $order - направление сортировки
     * @param bool $active - активность
     * @param bool $object - возвращать объект/массив
     * @return array|bool
     */
    public static function getMainMenu(string $sort = 'sort', string $order = 'ASC', bool $active = true, bool $object = true)
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
                if (empty($item->parent_id)) $res[0][$item->id] = $item;
                else $res[$item->parent_id][$item->id] = $item;
            }
        }
        return $res ?: false;
    }

    /**
     * Возвращает меню личного кабинета (+)
     * @param string $sort - сортировка
     * @param string $order - направление сортировки
     * @param bool $active - активность
     * @param bool $object - возвращать объект/массив
     * @return false
     */
    public static function getPersonalMenu(string $sort = 'sort', string $order = 'ASC', bool $active = true, bool $object = true)
    {
        $activity = !empty($active) ? 'AND p.active IS NOT NULL' : '';
        $sql = "
            SELECT p.id, p.parent_id, p.name, p.link, p.description, p.meta_d, p.meta_k, p.sort 
            FROM pages p 
            WHERE p.personal IS NOT NULL {$activity} 
            ORDER BY {$sort} {$order}";
        $db = Db::getInstance();
        $data = $db->query($sql, [],$object ? static::class : null);
        return $data ?: false;
    }

    /**
     * Получает информацию по текущей странице. Данные будут только у тех страниц, которые в shop.pages (+)
     * В остальных случаях информация по странице берется из данных товара, категории и т.д.
     * @param array $url
     * @return array|false
     */
    public static function getPageInfo(array $url)
    {
        if (!empty($url)) {
            $last = array_pop($url);
            $page = self::get(mb_strtolower($last['link']));
        } else $page = self::get('index');

        return $page ?? false;
    }

    /**
     * Возвращает инфо о странице из кэша или БД (+)
     * @param $name - название страницы
     * @return array|false|object
     */
    public static function get($name)
    {
        if ($data = Cache::getPage($name)) return $data;
        return self::getByField('link', $name);
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