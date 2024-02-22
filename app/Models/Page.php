<?php
namespace Models;

use System\Db;
use Utils\Cache;

class Page extends Model
{
    protected static $db_table = 'pages';

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
        $menu = Cache::getMenu($type);
        if (!empty($menu)) return $menu;

        switch ($type) {
            case 'main':
                $data = self::getMainMenu();
                break;
            case 'personal':
                $data = self::getPersonalMenu();
                break;
            case 'catalog':
                $data = self::getCatalogMenu();
                break;
            default:
                $data = null;
        }

        if (!empty($data)) Cache::saveMenu($type, $data);

        return $data;
    }

    /**
     * Возвращает главное меню
     * @param ?array $params
     * @return array
     */
    public static function getMainMenu(?array $params = [])
    {
        $params += ['active' => true];

        $db = Db::getInstance();
        $active = !empty($params['active']) ? 'AND active IS NOT NULL' : '';
        $sort = !empty($params['sort']) ? $params['sort'] : 'sort';
        $order = !empty($params['order']) ? strtoupper($params['order']) : 'ASC';

        $db->params = [];
        $db->sql = "
            SELECT id, menu, footer, parent_id, name, link, description, meta_d, meta_k, sort 
            FROM pages 
            WHERE menu IS NOT NULL {$active} 
            ORDER BY {$sort} {$order}";

        $data = $db->query();

        $menu = [];
        if (!empty($data) && is_array($data)) {
            foreach ($data as $item) {
                if (empty($item['parent_id'])) $menu[0][$item['id']] = $item;
                else $menu[$item['parent_id']][$item['id']] = $item;
            }
        }

        return $menu;
    }

    /**
     * Возвращает меню личного кабинета
     * @param array|null $params
     * @return array
     */
    public static function getPersonalMenu(?array $params = [])
    {
        $params += ['active' => true];

        $db = Db::getInstance();
        $active = !empty($params['active']) ? 'AND active IS NOT NULL' : '';
        $sort = !empty($params['sort']) ? $params['sort'] : 'sort';
        $order = !empty($params['order']) ? strtoupper($params['order']) : 'ASC';

        $db->params = [];
        $db->sql = "
            SELECT id, parent_id, name, link, description, meta_d, meta_k, sort 
            FROM pages  
            WHERE personal IS NOT NULL {$active} 
            ORDER BY {$sort} {$order}";

        $data = $db->query();
        return $data ?: [];
    }

    /**
     * Возвращает меню каталога
     * @return array
     */
    public static function getCatalogMenu()
    {
        $data = Category::getList();

        $menu = [];
        if (!empty($data) && is_array($data)) {
            foreach ($data as $item) {
                if (empty($item['parent_id'])) $menu[0][$item['id']] = $item;
                else $menu[$item['parent_id']][$item['id']] = $item;
            }
        }

        return $menu;
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