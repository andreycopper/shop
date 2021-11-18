<?php

namespace Models\Product;

use System\Db;
use Models\Model;

class Product extends Model
{
    protected static $table = 'products';
    public $id;
    public $xml_id;
    public $ie_id;
    public $name;
    public $active;
    public $active_from;
    public $active_to;
    public $articul;
    public $group_id;
    public $vendor_id;
    public $preview_image;
    public $preview_text;
    public $preview_text_type_id;
    public $detail_image;
    public $detail_text;
    public $detail_text_type_id;
    public $is_hit;
    public $is_new;
    public $is_action;
    public $is_recommend;
    public $tax_id;
    public $tax_included;
    public $quantity;
    public $discount;
    //public $price; // цена товара со скидкой (нужна исключительно для сортировки выборки)
    public $currency_id;
    public $unit_id;
    public $warranty;
    public $warranty_period_id;
    public $views;
    public $sort;
    public $created;
    public $updated;

    /**
     * Возвращает товар по id
     * @param int $id - id товара
     * @param bool $active - активность
     * @param bool $object - возвращать объект/массив
     * @return bool|mixed
     */
    public static function getById(int $id, bool $active = true, bool $object = true)
    {
        $params = [':id' => $id];
        $activity = !empty($active) ? 'AND p.active IS NOT NULL AND g.active IS NOT NULL AND v.active IS NOT NULL' : '';
        $sql = "
            SELECT 
                p.id, p.name, p.articul, p.quantity, p.discount,  p.views,
                p.is_hit, p.is_new, p.is_action, p.is_recommend,
                p.preview_image, p.preview_text, p.preview_text_type_id, ptt.name preview_text_type, 
                p.detail_image, p.detail_text, p.detail_text_type_id, dtt.name detail_text_type, 
                p.unit_id, u.name unit_name, u.sign unit, 
                p.warranty, p.warranty_period_id, wp.name warranty_name,
                p.group_id, g.name group_name, g.link group_link,
                p.vendor_id, v.name vendor, v.image vendor_image,
                p.tax_id, p.tax_included, t.name tax_name, t.value tax_value 
            FROM products p  
            LEFT JOIN `groups` g 
                ON p.group_id = g.id 
            LEFT JOIN vendors v 
                ON p.vendor_id = v.id 
            LEFT JOIN taxes t 
                ON p.tax_id = t.id 
            LEFT JOIN units u 
                ON p.unit_id = u.id 
            LEFT JOIN warranty_periods wp 
                ON p.warranty_period_id = wp.id 
            LEFT JOIN text_types ptt 
                ON p.preview_text_type_id = ptt.id 
            LEFT JOIN text_types dtt 
                ON p.detail_text_type_id = dtt.id 
            WHERE p.id = :id {$activity}";

        $db = Db::getInstance();
        $res = $db->query($sql, $params, $object ? static::class : null);
        return !empty($res) ? array_shift($res) : false;
    }

    /**
     * Возвращает список товаров
     * (по сути не нужен, т.к. getListByGroup делает тоже самое, но с учето групп и фильтров товаров. сделан для совместимости с базовым getList)
     * @param string $order - поле сортировки
     * @param string $sort - порядок сортировки
     * @param bool $active - активность
     * @param bool $object - возвращать объект/массив
     * @return array|bool
     */
    public static function getList(string $order = 'sort', string $sort = 'ASC', bool $active = true, bool $object = true)
    {
        $activity = !empty($active) ? 'WHERE p.active IS NOT NULL AND g.active IS NOT NULL AND v.active IS NOT NULL' : '';
        $sql = "
            SELECT 
                p.id, p.name, p.articul, p.quantity, p.discount,  p.views,
                p.is_hit, p.is_new, p.is_action, p.is_recommend,
                p.preview_image, p.preview_text, p.preview_text_type_id, ptt.name preview_text_type, 
                p.detail_image, p.detail_text, p.detail_text_type_id, dtt.name detail_text_type, 
                p.unit_id, u.name unit_name, u.sign unit_sign, 
                p.warranty, p.warranty_period_id, wp.name warranty_name,
                p.group_id, g.name group_name, g.link group_link,
                p.vendor_id, v.name vendor, v.image vendor_image,
                p.tax_id, p.tax_included, t.name tax_name, t.value tax_value 
            FROM products p  
            LEFT JOIN `groups` g 
                ON p.group_id = g.id 
            LEFT JOIN vendors v 
                ON p.vendor_id = v.id 
            LEFT JOIN taxes t 
                ON p.tax_id = t.id 
            LEFT JOIN units u 
                ON p.unit_id = u.id 
            LEFT JOIN warranty_periods wp 
                ON p.warranty_period_id = wp.id 
            LEFT JOIN text_types ptt 
                ON p.preview_text_type_id = ptt.id 
            LEFT JOIN text_types dtt 
                ON p.detail_text_type_id = dtt.id 
            {$activity} 
            ORDER BY p.{$order}, p.created, p.id {$sort}";

        $db = Db::getInstance();
        $items = $db->query($sql, [], $object ? static::class : null);
        return $items ?: false;
    }

    /**
     * Возвращает список товаров по id группы
     * @param int|null $group_id - группа товаров (пусто - все товары)
     * @param int $price_type_id - основной тип цены для пользователя
     * @param int|null $page_number - номер страницы
     * @param int|null $page_count - количество товаров на странице
     * @param array $filters - массив фильтров товара
     * @param string $sort - порядок сортировки
     * @param string $order - поле сортировки
     * @param bool $active - активность
     * @param bool $object - возвращать объект/массив
     * @return array|bool
     */
    public static function getListByGroup(
        int $group_id = null,
        int $price_type_id = 2,
        int $page_number = null,
        int $page_count = null,
        array $filters = [],
        string $sort = 'sort',
        string $order = 'ASC',
        bool $active = true,
        bool $object = true
    )
    {
        $params = ['price_type_id' => $price_type_id];
        $filter = self::getFilterString($filters);
        $params += self::getFilterParams($filters);

        $group = !empty($group_id) ? 'AND p.group_id = :group' : '';
        $activity = !empty($active) ? 'AND p.active IS NOT NULL AND g.active IS NOT NULL AND v.active IS NOT NULL' : '';
        $offset = !empty($page_number) && !empty($page_count) ? $page_count * ($page_number - 1) : 0;
        $limit = !empty($page_count) ? "LIMIT {$offset}, {$page_count}" : '';
        if ($sort !== 'price') $sort =  'p.' . $sort;
        if (!empty($group_id)) $params['group'] = $group_id;

        $sql = "
            SELECT 
                p.id, p.name, p.articul, p.quantity, p.discount, 
                ROUND(pp.price * cr.rate * (100 - COALESCE(p.discount, 0)) / 100) price, 
                p.views, p.is_hit, p.is_new, p.is_action, p.is_recommend, 
                p.preview_image, p.preview_text, p.preview_text_type_id, ptt.name preview_text_type, 
                p.detail_image, p.detail_text, p.detail_text_type_id, dtt.name detail_text_type, 
                p.unit_id, u.name unit_name, u.sign unit, 
                p.warranty, p.warranty_period_id, wp.name warranty_name, 
                p.group_id, g.name group_name, g.link group_link, 
                p.vendor_id, v.name vendor, v.image vendor_image, 
                p.tax_id, p.tax_included, t.name tax_name, t.value tax_value 
            FROM products p 
            LEFT JOIN `groups` g ON p.group_id = g.id 
            LEFT JOIN product_prices pp ON p.id = pp.product_id AND pp.price_type_id = :price_type_id
            LEFT JOIN currency_rates cr ON pp.currency_id = cr.currency_id
            LEFT JOIN vendors v ON p.vendor_id = v.id 
            LEFT JOIN taxes t ON p.tax_id = t.id 
            LEFT JOIN units u ON p.unit_id = u.id 
            LEFT JOIN warranty_periods wp ON p.warranty_period_id = wp.id 
            LEFT JOIN text_types ptt ON p.preview_text_type_id = ptt.id 
            LEFT JOIN text_types dtt ON p.detail_text_type_id = dtt.id 
            WHERE 1 {$group} {$activity} {$filter} 
            ORDER BY {$sort} {$order}, p.created {$order}, p.id {$order} 
            {$limit}";

        $db = Db::getInstance();
        $items = $db->query($sql, $params, $object ? static::class : null);
        return $items ?: false;
    }

    /**
     * Возвращает количество товаров в конкретной группе
     * @param array $group - массив групп товаров (пустой массив - все товары)
     * @param int $price_type_id - используемый тип цен
     * @param array $filters - массив фильтров товара
     * @param bool $active - активность
     * @return false|string
     */
    public static function getCountByGroup(array $group = [], int $price_type_id = 2, array $filters = [], bool $active = true)
    {
        $params = ['price_type_id' => $price_type_id];
        $filter = self::getFilterString($filters);
        $params += self::getFilterParams($filters);

        $groups = !empty($group) ? ('AND p.group_id IN (' . implode(',', $group) . ')') : '';
        $activity = !empty($active) ? ((!empty($groups) ? 'AND ' : '') . 'p.active IS NOT NULL AND g.active IS NOT NULL AND v.active IS NOT NULL') : '';
        $sql = "
            SELECT 
                count(p.id) count 
            FROM products p 
            LEFT JOIN `groups` g 
                ON p.group_id = g.id 
            LEFT JOIN vendors v ON p.vendor_id = v.id 
            LEFT JOIN product_prices pp ON p.id = pp.product_id AND pp.price_type_id = :price_type_id
            LEFT JOIN currency_rates cr ON pp.currency_id = cr.currency_id
            WHERE 1 {$groups} {$activity} {$filter}
        ";

        $db = Db::getInstance();
        $res = $db->query($sql, $params);
        return $res ? array_shift($res)['count'] : false;
    }

    /**
     * Возвращает строку запроса для используемых фильтров
     * @param array $filters - массив фильтров товара
     * @return string
     */
    private static function getFilterString(array $filters = [])
    {
        $filter = '';
        if (!empty($filters) && is_array($filters)) {
            foreach ($filters as $fname => $fvalue) {
                switch ($fname) {
                    case 'price':
                        $filter .= ' AND (ROUND(pp.price * cr.rate * (100 - COALESCE(p.discount, 0)) / 100) BETWEEN :price_min AND :price_max)';
                        break;
                    case 'actions':
                        if (in_array('new', $fvalue)) $filter .= ' AND p.is_new IS NOT NULL';
                        if (in_array('hit', $fvalue)) $filter .= ' AND p.is_hit IS NOT NULL';
                        if (in_array('recommend', $fvalue)) $filter .= ' AND p.is_recommend IS NOT NULL';
                        if (in_array('action', $fvalue)) $filter .= ' AND p.is_action IS NOT NULL';
                        break;
                    case 'vendors':
                        $str = implode(', ', $fvalue);
                        $filter .= " AND v.id IN ({$str})";
                        break;
                    default:
                        $filter .= " AND {$fname} = :{$fname}";
                }
            }
        }
        return $filter;
    }

    /**
     * Возвращает массив параметров для запроса
     * @param array $filters - массив фильтров товара
     * @return array
     */
    private static function getFilterParams(array $filters)
    {
        $params = [];
        foreach ($filters as $fname => $fvalue) {
            switch ($fname) {
                case 'actions':
                case 'vendors':
                    break;
                case 'price':
                    $params['price_min'] = $fvalue[0];
                    $params['price_max'] = $fvalue[1];
                    break;
                default:
                    $filter .= " AND {$fname} = :{$fname}";
                    $params[$fname] = $fvalue;
            }
        }
        return $params;
    }

    /**
     * Возвращает товар с ценами
     * @param int $id - id товара
     * @param array $price_types - массив типов цен (пустой массив - все типы цен)
     * @param bool $active - активность
     * @param bool $object - возвращать объект/массив
     * @return bool|mixed
     */
    public static function getPrice(int $id, array $price_types = [2], bool $active = true, bool $object = true)
    {
        $item = self::getById($id, $active, $object);
        if (!empty($item)) {
            $item->prices = ProductPrice::getPrices($id, $price_types, $active);
            $item->images = ProductImage::getByProductId($id);
            $item->stores = ProductStore::getQuantities($id);
        }
        return $item ?: false;
    }

    /**
     * Возвращает список товаров определенной группы с ценами
     * @param int|null $group_id
     * @param int $price_type_id - основной тип цены для пользователя
     * @param array $price_types - массив типов цен (пустой массив - все типы цен)
     * @param int|null $page_number
     * @param int|null $page_count
     * @param array $filters - массив фильтров
     * @param string $order - поле сортировки
     * @param string $sort - порядок сортировки
     * @param bool $active - активность
     * @param bool $object - возвращать объект/массив
     * @return array|bool|mixed
     */
    public static function getPriceList(
        int $group_id = null,
        int $price_type_id = 2,
        array $price_types = [],
        int $page_number = null,
        int $page_count = null,
        array $filters = [],
        string $sort = 'sort',
        string $order = 'ASC',
        bool $active = true,
        bool $object = true
    )
    {
        $items = self::getListByGroup($group_id, $price_type_id, $page_number, $page_count, $filters, $sort, $order, $active, $object);
        if (!empty($items) && is_array($items)) {
            foreach ($items as $item) {
                $item->prices = ProductPrice::getPrices($item->id, $price_types, $active);
            }
        }
        return $items ?: false;
    }

    /**
     * Возвращает диапазон цен группы товаров для фильтра
     * @param int|null $group_id - группа товаров
     * @param int $price_type_id - используемый тип цен
     * @param bool $active - активность
     * @return false|mixed|null
     */
    public static function getRange(int $group_id = null, int $price_type_id = 2, bool $active = true)
    {
        $activity = !empty($active) ? 'AND p.active IS NOT NULL AND g.active IS NOT NULL AND v.active IS NOT NULL' : '';
        $params = ['price_type_id' => $price_type_id];
        $group = !empty($group_id) ? 'AND p.group_id = :group' : '';
        if (!empty($group_id)) $params['group'] = $group_id;

        $sql = "
            SELECT 
                min(ROUND(pp.price * cr.rate * (100 - COALESCE(p.discount, 0)) / 100)) min,
                max(ROUND(pp.price * cr.rate * (100 - COALESCE(p.discount, 0)) / 100)) max
            FROM products p 
            LEFT JOIN `groups` g ON p.group_id = g.id 
            LEFT JOIN product_prices pp ON p.id = pp.product_id AND pp.price_type_id = :price_type_id
            LEFT JOIN currency_rates cr ON pp.currency_id = cr.currency_id 
            LEFT JOIN vendors v ON p.vendor_id = v.id 
            WHERE 1 {$group} {$activity}
        ";
        $db = Db::getInstance();
        $res = $db->query($sql, $params);

        return $res ? array_shift($res) : false;
    }

    /**
     * Возвращает список производителей группы товаров для фильтра
     * @param int|null $group_id - группа товаров
     * @param bool $active - активность
     * @return false
     */
    public static function getVendors(int $group_id = null, bool $active = true)
    {
        $activity = !empty($active) ? 'AND p.active IS NOT NULL AND g.active IS NOT NULL AND v.active IS NOT NULL' : '';
        $group = !empty($group_id) ? 'AND p.group_id = :group' : '';
        if (!empty($group_id)) $params['group'] = $group_id;

        $sql = "
            SELECT DISTINCT v.id, v.name 
            FROM products p 
            LEFT JOIN `groups` g ON p.group_id = g.id 
            LEFT JOIN vendors v ON p.vendor_id = v.id 
            WHERE 1 {$group} {$activity}
            ORDER BY v.id
        ";
        $db = Db::getInstance();
        $res = $db->query($sql, $params);

        return $res ?: false;
    }

    /**
     * Добавляет товару просмотр
     * @param int $id - id товара
     * @return bool
     */
    public static function addProductView(int $id)
    {
        $params = ['id' => $id];
        $sql = "UPDATE products SET views = views + 1 WHERE products.id = :id";
        $db = Db::getInstance();
        return $db->execute($sql, $params);
    }
}
