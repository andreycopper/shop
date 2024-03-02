<?php
namespace Models\Product;

use System\Db;
use Models\Model;

class Product extends Model
{
    protected static $db_table = 'products';

    public int $id;
    public ?int $xml_id = null;
    public ?int $ie_id = null;
    public string $name;
    public ?int $active = null;
    public ?string $active_from = null;
    public ?string $active_to = null;

    public ?string $articul = null;
    public int $category_id;
    public int $vendor_id;

    public ?string $preview_image = null;
    public ?string $preview_text = null;
    public int $preview_text_type_id = 1;
    public ?string $detail_image = null;
    public ?string $detail_text = null;
    public int $detail_text_type_id = 1;

    public ?int $is_hit = null;
    public ?int $is_new = null;
    public ?int $is_action = null;
    public ?int $is_recommend = null;

    public int $tax_id = 1;
    public ?int $tax_included = null;
    public int $quantity = 0;
    public ?int $discount = null;
    public float $price;

    public int $currency_id = 1;
    public int $unit_id = 1;
    public ?int $is_warranty = null;
    public int $warranty_period_id = 2;

    public int $views = 0;
    public int $sort = 500;
    public string $created;
    public ?string $updated = null;

    /**
     * Возвращает товар с ценами
     * @param int $id - id товара
     * @param array $params
     * @return array
     */
    public static function get(int $id, array $params = [])
    {
        $item = self::getById($id, $params);
        $item['prices'] = self::getPrice($item['id'], $params);
        //$item['images'] = ProductImage::getByProductId($id);
        //$item['stores'] = ProductStore::getQuantities($id);
        return $item;
    }

    /**
     * Возвращает товар по его id с ценами
     * @param int $product_id - id товара
     * @param array $params
     * @return array
     */
    public static function getPrice(int $product_id, array $params = [])
    {
        return
            defined(SHOW_ALL_PRICES_CATALOG) ?
                ProductPrice::getPrices($product_id, $params['price_types']) :
                ProductPrice::getPrice($product_id, $params['price_type_id']);
    }

    /**
     * Возвращает товар по его id без цен
     * @param int $id - id товара
     * @param array $params
     * @return array|null
     */
    public static function getById(int $id, array $params = [])
    {
        $params += ['price_type_id' => 2, 'active' => true, 'object' => false];

        $db = Db::getInstance();
        $db->params = ['id' => $id, 'price_type_id' => $params['price_type_id']];
        $active = !empty($params['active']) ? 'AND p.active IS NOT NULL AND cat.active IS NOT NULL AND v.active IS NOT NULL' : '';

        $db->sql = "
            SELECT 
                p.id, p.xml_id, p.ie_id, p.name, p.active, p.active_from, p.active_to, p.articul, 
                p.category_id, cat.name AS category, cat.link AS category_link, 
                p.vendor_id, v.name AS vendor, v.image AS vendor_image, 
                p.preview_image, p.preview_text, p.preview_text_type_id, ptt.name AS preview_text_type, 
                p.detail_image, p.detail_text, p.detail_text_type_id, dtt.name AS detail_text_type, 
                p.is_hit, p.is_new, p.is_action, p.is_recommend, 
                p.tax_id, p.tax_included, t.name AS tax, t.value AS tax_value, 
                p.quantity, p.discount,
                pp.price * cr.rate AS price,    
                pp.price * cr.rate * (100 - COALESCE(IF(pp.price_type_id = 2, p.discount, 0), 0)) / 100 AS price_discount,    
                pp.price_type_id, pt.name price_type, 
                pp.currency_id, c.iso AS currency, c.logo AS currency_logo, c.sign AS currency_sign, cr.rate currency_rate, 
                p.unit_id, u.name AS unit, u.sign AS unit_sign,
                p.is_warranty, p.warranty_period_id, wp.name AS warranty, 
                p.views, p.sort, p.created, p.updated 
            FROM products p 
            LEFT JOIN shop.categories cat ON p.category_id = cat.id 
            LEFT JOIN product_prices pp ON p.id = pp.product_id AND pp.price_type_id = :price_type_id
            LEFT JOIN price_types pt on pp.price_type_id = pt.id 
            LEFT JOIN currencies c ON c.id = pp.currency_id 
            LEFT JOIN currency_rates cr ON pp.currency_id = cr.currency_id
            LEFT JOIN vendors v ON p.vendor_id = v.id 
            LEFT JOIN taxes t ON p.tax_id = t.id 
            LEFT JOIN units u ON p.unit_id = u.id 
            LEFT JOIN warranty_periods wp ON p.warranty_period_id = wp.id 
            LEFT JOIN text_types ptt ON p.preview_text_type_id = ptt.id 
            LEFT JOIN text_types dtt ON p.detail_text_type_id = dtt.id 
            WHERE p.id = :id {$active}";

        $data = $db->query();
        return $data ? array_shift($data) : null;
    }

    /**
     * Возвращает массив товаров с ценами
     * @param array $params
     * @return array
     */
    public static function getPriceList(array $params = [])
    {
        $items = self::getList($params);

        if (!empty($items) && is_array($items)) {
            foreach ($items as &$item) {
                $item['prices'] =
                    defined(SHOW_ALL_PRICES_CATALOG) ?
                        ProductPrice::getPrices($item['id'], $params['price_types']) :
                        ProductPrice::getPrice($item['id'], $item['price_type_id']);
                //$item['images'] = ProductImage::getImages($item['id'], $params);
                //$item['stores'] = ProductStore::getStores($item['id'], $params);
            }
        }

        return $items;
    }

    /**
     * Возвращает массив товаров без цен
     * @param array $params
     * @return array
     */
    public static function getList(array $params = [])
    {
        $params += [
            'price_type_id' => 2,
            'page_number' => 0,
            'elements_per_page' => 20,
            'sort' => 'price',
            'order' => 'ASC',
            'active' => true,
            'object' => false,
        ];

        $db = Db::getInstance();
        $db->params = ['price_type_id' => $params['price_type_id']];

        $category = !empty($params['category_id']) ? 'AND p.category_id = :category_id' : '';
        $active = !empty($params['active']) ? 'AND p.active IS NOT NULL AND cat.active IS NOT NULL AND v.active IS NOT NULL' : '';
        if ($params['sort'] !== 'price') $params['sort'] =  "p.{$params['sort']}";
        $offset = $params['page_number'] * $params['elements_per_page'];

        $order = strtolower($params['order']) === 'desc' ? 'DESC' : 'ASC';

        if (!empty($params['category_id'])) $db->params['category_id'] = $params['category_id'];

        $filter = self::getFilterString($params['filters']);
        $db->params += self::getFilterParams($params['filters']);
        $db->sql = "
            SELECT 
                p.id, p.xml_id, p.ie_id, p.name, p.active, p.active_from, p.active_to, p.articul, 
                p.category_id, cat.name AS category, cat.link AS category_link, 
                p.vendor_id, v.name AS vendor, v.image AS vendor_image, 
                p.preview_image, p.preview_text, p.preview_text_type_id, ptt.name AS preview_text_type, 
                p.detail_image, p.detail_text, p.detail_text_type_id, dtt.name AS detail_text_type, 
                p.is_hit, p.is_new, p.is_action, p.is_recommend, 
                p.tax_id, p.tax_included, t.name AS tax, t.value AS tax_value, 
                p.quantity, p.discount,
                pp.price * cr.rate AS price, 
                pp.price * cr.rate * (100 - COALESCE(IF(pp.price_type_id = 2, p.discount, 0), 0)) / 100 AS price_discount,    
                pp.price_type_id, pt.name price_type, 
                pp.currency_id, c.iso AS currency, c.logo AS currency_logo, c.sign AS currency_sign, cr.rate currency_rate, 
                p.unit_id, u.name AS unit, u.sign AS unit_sign,
                p.is_warranty, p.warranty_period_id, wp.name AS warranty, 
                p.views, p.sort, p.created, p.updated 
            FROM products p 
            LEFT JOIN shop.categories cat ON p.category_id = cat.id 
            LEFT JOIN product_prices pp ON p.id = pp.product_id AND pp.price_type_id = :price_type_id
            LEFT JOIN price_types pt on pp.price_type_id = pt.id 
            LEFT JOIN currencies c ON c.id = 1
            LEFT JOIN currency_rates cr ON pp.currency_id = cr.currency_id
            LEFT JOIN vendors v ON p.vendor_id = v.id 
            LEFT JOIN taxes t ON p.tax_id = t.id 
            LEFT JOIN units u ON p.unit_id = u.id 
            LEFT JOIN warranty_periods wp ON p.warranty_period_id = wp.id 
            LEFT JOIN text_types ptt ON p.preview_text_type_id = ptt.id 
            LEFT JOIN text_types dtt ON p.detail_text_type_id = dtt.id 
            WHERE 1 {$category} {$active} {$filter} 
            ORDER BY {$params['sort']} {$order}, p.created {$params['order']}, p.id {$params['order']} 
            LIMIT {$offset}, {$params['elements_per_page']}";

        $data = $db->query();
        return $data ?: [];
    }

    /**
     * Возвращает количество товаров в выборке
     * @param array $params
     * @return int
     */
    public static function getCount(array $params = [])
    {
        $params += ['active' => true, 'object' => false,];

        $db = Db::getInstance();
        $db->params = ['price_type_id' => $params['price_type_id']];

        $category = !empty($params['category_id']) ? 'AND p.category_id = :category_id' : '';
        $active = !empty($params['active']) ? 'AND p.active IS NOT NULL AND cat.active IS NOT NULL AND v.active IS NOT NULL' : '';


        if (!empty($params['category_id'])) $db->params['category_id'] = $params['category_id'];

        $filter = self::getFilterString($params['filters']);
        $db->params += self::getFilterParams($params['filters']);
        $db->sql = "
            SELECT 
                count(p.id) count 
            FROM products p 
            LEFT JOIN shop.categories cat ON p.category_id = cat.id 
            LEFT JOIN product_prices pp ON p.id = pp.product_id AND pp.price_type_id = :price_type_id
            LEFT JOIN currency_rates cr ON pp.currency_id = cr.currency_id
            LEFT JOIN vendors v ON p.vendor_id = v.id 
            WHERE 1 {$category} {$active} {$filter}";

        $data = $db->query();
        return $data ? array_shift($data)['count'] : 0;
    }

    /**
     * Возвращает диапазон цен группы товаров для фильтра
     * @param array $params
     * @return array|null
     */
    public static function getRange(array $params = [])
    {
        $params += ['price_type_id' => 2, 'active' => true, 'object' => false,];

        $db = Db::getInstance();
        $db->params = ['price_type_id' => $params['price_type_id']];

        $category = !empty($params['category_id']) ? 'AND p.category_id = :category_id' : '';
        $active = !empty($params['active']) ? 'AND p.active IS NOT NULL AND cat.active IS NOT NULL AND v.active IS NOT NULL' : '';

        if (!empty($params['category_id'])) $db->params['category_id'] = $params['category_id'];

        //$filter = self::getFilterString($filters);
        //$params += self::getFilterParams($filters);
        $db->sql = "
            SELECT 
                min(pp.price * cr.rate * (100 - COALESCE(IF(pp.price_type_id = 2, p.discount, 0), 0)) / 100) AS min,  
                max(pp.price * cr.rate * (100 - COALESCE(IF(pp.price_type_id = 2, p.discount, 0), 0)) / 100) AS max 
            FROM products p 
            LEFT JOIN shop.categories cat ON p.category_id = cat.id 
            LEFT JOIN product_prices pp ON p.id = pp.product_id AND pp.price_type_id = :price_type_id
            LEFT JOIN currency_rates cr ON pp.currency_id = cr.currency_id
            LEFT JOIN vendors v ON p.vendor_id = v.id 
            WHERE 1 {$category} {$active}";

        $data = $db->query();

        return $data ? array_shift($data) : null;
    }

    /**
     * Возвращает массив товаров без цен
     * @param array $params
     * @return array|null
     */
    public static function getVendors(array $params = [])
    {
        $params += ['active' => true, 'object' => false,];

        $db = Db::getInstance();
        $db->params = [];

        $category = !empty($params['category_id']) ? 'AND p.category_id = :category_id' : '';
        $active = !empty($params['active']) ? 'AND p.active IS NOT NULL AND cat.active IS NOT NULL AND v.active IS NOT NULL' : '';

        if (!empty($params['category_id'])) $db->params['category_id'] = $params['category_id'];

        //$filter = self::getFilterString($filters);
        //$params += self::getFilterParams($filters);
        $db->sql = "
            SELECT DISTINCT 
                v.id, v.name 
            FROM products p 
            LEFT JOIN shop.categories cat ON p.category_id = cat.id 
            LEFT JOIN vendors v ON p.vendor_id = v.id 
            WHERE 1 {$category} {$active}";

        $data = $db->query();
        return $data ?: null;
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
                $fname = preg_replace('/[^a-z]/', '', $fname);

                switch ($fname) {
                    case 'price':
                        $filter .= ' AND (pp.price * cr.rate * (100 - COALESCE(IF(pp.price_type_id = 2, p.discount, 0), 0)) / 100) BETWEEN :price_min AND :price_max';
                        break;
                    case 'actions':
                        if (in_array('new', $fvalue)) $filter .= ' AND p.is_new IS NOT NULL';
                        if (in_array('hit', $fvalue)) $filter .= ' AND p.is_hit IS NOT NULL';
                        if (in_array('recommend', $fvalue)) $filter .= ' AND p.is_recommend IS NOT NULL';
                        if (in_array('action', $fvalue)) $filter .= ' AND p.is_action IS NOT NULL';
                        break;
                    case 'vendors':
                        $str = implode(', ', $fvalue);
                        $str = preg_replace('/[^0-9,]/', '', $str);
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
            $fname = preg_replace('/[^a-z]/', '', $fname);

            switch ($fname) {
                case 'actions':
                case 'vendors':
                    break;
                case 'price':
                    $params['price_min'] = preg_replace('/[^0-9]/', '', min($fvalue[0], $fvalue[1]));
                    $params['price_max'] = preg_replace('/[^0-9]/', '', max($fvalue[0], $fvalue[1]));
                    break;
                default:
                    $params[$fname] = preg_replace('/[^0-9]/', '', $fvalue);
            }
        }

        return $params;
    }

    /**
     * Добавляет товару просмотр
     * @param int $id - id товара
     * @return bool
     */
    public static function addView(int $id)
    {
        $db = Db::getInstance();
        $db->params = ['id' => $id];
        $db->sql = "UPDATE shop.products SET views = views + 1 WHERE products.id = :id";
        return $db->execute();
    }
}
