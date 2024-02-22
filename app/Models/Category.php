<?php
namespace Models;

use System\Db;
use Utils\Cache;

class Category extends Model
{
    protected static $db_table = 'shop.categories';

    public int $id;
    public ?int $active = 1;
    public ?string $active_from = null;
    public ?string $active_to = null;
    public ?int $parent_id = null;
    public ?string $link = null;
    public string $name;
    public ?string $image = null;
    public ?string $description = null;
    public int $description_type_id = 1;
    //public string $description_type;
    //public ?int $count;
    public int $sort = 500;
    public string $created;
    public ?string $updated = null;

    public static function getList(?array $params = [])
    {
        $params += ['active' => true, 'object' => false];

        $db = Db::getInstance();
        $active = !empty($params['active']) ? 'c.active IS NOT NULL' : '(c.active IS NOT NULL OR c.active IS NULL)';
        $sort = !empty($params['sort']) ? $params['sort'] : 'id';
        $order = !empty($params['order']) ? strtoupper($params['order']) : 'ASC';
        $parent = !empty($params['parent_id']) ? 'AND c.parent_id = :parent_id' : '';

        $db->params = [];
        if (!empty($params['parent_id'])) $db->params['parent_id'] = $params['parent_id'];

        $db->sql = "
            SELECT 
                c.id, c.active, c.parent_id, c.name, REPLACE(c.link, '_', '-') AS link, c.image, c.description, 
                c.description_type_id, tt.name as description_type, 
                c.sort, count(p.id) AS count, c.created, c.updated 
            FROM shop.categories c 
            LEFT JOIN shop.text_types tt ON c.description_type_id = tt.id 
            LEFT JOIN shop.products p on c.id = p.category_id AND p.active IS NOT NULL
            WHERE {$active} {$parent}
            GROUP BY c.id 
            ORDER BY c.{$sort} {$order}, c.id ASC";

        $data = $db->query();
        return $data ?: null;
    }

    /**
     * Возвращает категорию товаров
     * @param string $name - название категории
     * @return array|false|object
     */
    public static function get(string $name)
    {
        $category = Cache::getCategory($name);
        if (!empty($category)) return $category;

        $data = self::getByName($name);
        $data['sub_categories'] = self::getList(['parent_id' => $data['id']]);

        if (!empty($data)) Cache::saveCategory($name, $data);
        return $data;
    }

    public static function getByName(string $name, ?array $params = [])
    {
        $params += ['active' => true, 'object' => false];

        $db = Db::getInstance();
        $active = !empty($params['active']) ? 'AND c.active IS NOT NULL' : '';
        $sort = !empty($params['sort']) ? $params['sort'] : 'id';
        $order = !empty($params['order']) ? strtoupper($params['order']) : 'ASC';

        $db->params = ['name' => $name];
        $db->sql = "
            SELECT 
                c.id, c.active, c.parent_id, c.name, REPLACE(c.link, '_', '-') AS link, c.image, c.description, 
                c.description_type_id, tt.name as description_type, 
                c.sort, count(p.id) AS count, c.created, c.updated 
            FROM shop.categories c 
            LEFT JOIN shop.text_types tt ON c.description_type_id = tt.id 
            LEFT JOIN shop.products p on c.id = p.category_id AND p.active IS NOT NULL
            WHERE c.link = :name {$active} 
            GROUP BY c.id 
            ORDER BY c.{$sort} {$order}, c.id ASC";

        $data = $db->query();
        return !empty($data[0]) ? array_shift($data) : null;
    }
}
