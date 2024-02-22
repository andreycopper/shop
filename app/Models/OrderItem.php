<?php
namespace Models;

use System\Db;
use Entity\User;
use System\Logger;
use Exceptions\DbException;
use Exceptions\EditException;
use Exceptions\UserException;
use Models\Product\ProductPrice;
use Models\User\User as ModelUser;

class OrderItem extends Model
{
    protected static $db_table = 'shop.order_items';

    public int $id;                // id записи
    public ?int $order_id = null;  // id заказа
    public ?int $qorder_id = null; // id быстрого заказа
    public int $user_id = 2;       // id пользователя
    public string $user_hash;      // hash пользователя

    public int $product_id;        // id товара
    public int $price_type_id = 2; // id типа цен
    public ?int $coupon_id = null; // id купона

    public int $count; // количество

    public ?float $discount = null; // скидка в %
    public ?float $tax = null;      // размер налога в %

    public float $price;                      // цена
    public ?float $price_nds = null;          // НДС цены
    public ?float $price_discount = null;     // цена со скидкой
    public ?float $price_discount_nds = null; // НДС цены со скидкой

    public float $sum;                      // сумма
    public ?float $sum_nds = null;          // НДС суммы
    public ?float $sum_discount = null;     // сумма со скидкой
    public ?float $sum_discount_nds = null; // НДС суммы со скидкой

    public ?float $delivery = null;     // сумма доставки
    public ?float $delivery_nds = null; // НДС суммы доставки

    public \DateTime $created;         // дата создания записи
    public ?\DateTime $updated = null; // дата создания записи

    /**
     * Получает количество товаров в корзине
     * @param User $user - покупатель
     * @return int
     */
    public static function getCount(User $user)
    {
        if (empty($_COOKIE['user'])) return 0;

        $db = Db::getInstance();
        $db->params = ['user_id' => $user->id];

        $where = ($user->id === 2) ? 'AND user_hash = :user_hash' : '';
        if ($user->id === 2) $db->params['user_hash'] = $_COOKIE['user'];

        $db->sql = "
            SELECT sum(count) AS count 
            FROM shop.order_items 
            WHERE user_id = :user_id {$where} AND order_id IS NULL AND qorder_id IS NULL";

        $data = $db->query();
        return !empty($data[0]['count']) ? $data[0]['count'] : 0;
    }


























































    /**
     * Проверяет добавляется новый элемент или редактируется существующий
     * @return bool
     */
    public function isNew(): bool
    {
        return empty(self::getProductByUser($this->product_id, $this->user_id, $this->user_hash));
    }

    /**
     * Получает актуальную корзину пользователя
     * @param int $user_id - id пользователя
     * @return array|false
     */
    public static function getCart(int $user_id)
    {
        $items = self::getListByUser($user_id, $_COOKIE['user']);

        if (!empty($items) && is_array($items)) {
            $count_items = 0;
            $count_absent = 0;
            $sum = 0;
            $sum_nds = 0;
            $sum_discount = 0;
            $sum_discount_nds = 0;
            $absent = [];

            foreach ($items as $key => $item) {
                if ($item->count > $item->quantity) { // на складах недостаточно товара
                    $absent[] = $item;
                    $count_absent += $item->count;
                    unset($items[$key]);
                    continue;
                }

                $outdated = str_replace('-', '', explode(' ', $item->updated ?: $item->created)[0]) < date('Ymd');

                if ($outdated) { // товар в корзине больше суток
                    $message = 'Цены товаров, добавленных в корзину более суток назад обновлены';
                    $price = ProductPrice::getPrice($item->product_id, $item->price_type_id); // актуальные цены товара
                    $item->price = $price->price;
                    $item->sum = $item->price * $item->count;
                    $item->updated = date('Y-m-d H:i:s');

                    if (!empty($price->tax_value)) { // НДС
                        $item->tax = floatval($price->tax_value);
                        $item->price_nds = round($item->price * $item->tax / (100 + $item->tax), 4);
                        $item->sum_nds = round($item->sum * $item->tax / (100 + $item->tax), 4);
                    }

                    if (!empty($price->discount)) { // скидка
                        $item->discount = $price->discount;
                        $item->price_discount = round($item->price * (100 - $item->discount) / 100, 4);
                        $item->sum_discount = round($item->price_discount * $item->count, 4);
                        $item->economy = $item->price - $item->price_discount;
                        $item->sum_economy = $item->sum - $item->sum_discount;

                        if (!empty($item->tax)) { // НДС
                            $item->price_discount_nds = round($item->price_discount * $item->tax / (100 + $item->tax), 4);
                            $item->sum_discount_nds = round($item->sum_discount * $item->tax / (100 + $item->tax), 4);
                        }
                    }

                    if (!self::factory($item)->save()) Logger::getInstance()->error(new EditException('Не удалось обновить в корзине товар id=' . $item->id));
                }

                $count_items += $item->count;
                $sum += $item->sum;
                $sum_nds += $item->sum_nds ?: 0;
                $sum_discount += $item->sum_discount ?: $item->sum;
                $sum_discount_nds += $item->sum_discount_nds ?: $item->sum_nds;
            }

            $result = [
                'items'            => $items,
                'absent'           => $absent,
                'count_items'      => $count_items,
                'count_absent'     => $count_absent,
                'sum'              => $sum,
                'sum_nds'          => $sum_nds,
                'sum_discount'     => $sum_discount,
                'sum_discount_nds' => $sum_discount_nds,
                'economy'          => $sum - $sum_discount,
                'coupon'           => $coupon ?? null,
                'message'          => $message ?? ''
            ];
        }

        return $result ?? false;
    }

    /**
     * Получает товар в корзине пользователя по id товара и id пользователя
     * @param int $product_id - id товара
     * @param int $user_id - id пользователя
     * @param string $user_hash - хэш пользователя (нужен для неавторизованного)
     * @param bool $object - возвращать объект/массив
     * @return false|mixed
     */
    public static function getProductByUser(int $product_id, int $user_id, string $user_hash = '', bool $object = true)
    {
        $userHash = ($user_id === 2) ? 'AND oi.user_hash = :user_hash' : '';
        $params = [
            ':product_id' => $product_id,
            ':user_id' => $user_id,
        ];
        if ($user_id === 2) $params['user_hash'] = $user_hash;
        $sql = "
            SELECT oi.id, oi.order_id, oi.user_id, oi.user_hash, oi.product_id, oi.price_type_id, oi.count, oi.coupon_id, 
                   oi.discount, oi.price, oi.price_nds, oi.sum, oi.sum_nds, oi.price_discount, oi.price_discount_nds, 
                   oi.sum_discount_nds, oi.sum_discount, (oi.price - oi.price_discount) economy, 
                   (oi.sum - oi.sum_discount) sum_economy, oi.created, oi.updated 
            FROM order_items oi 
            WHERE oi.user_id = :user_id AND oi.product_id = :product_id {$userHash} AND oi.order_id IS NULL AND oi.qorder_id IS NULL";
        $db = Db::getInstance();
        $data = $db->query($sql, $params, $object ? static::class : null);
        return !empty($data) ? array_shift($data) : false;
    }

    /**
     * Получает список товаров в корзине по id пользователя
     * @param int $user_id
     * @param string $user_hash
     * @param bool $active
     * @param bool $object
     * @return array|false
     */
    public static function getListByUser(int $user_id, string $user_hash = '', bool $active = true, bool $object = true)
    {
        $activity = !empty($active) ? 'AND p.active IS NOT NULL' : '';
        $params = [':user_id' => $user_id];
        $userHash = ($user_id === 2) ? 'AND oi.user_hash = :user_hash' : '';
        if ($user_id === 2) $params[':user_hash'] = $user_hash;
        $sql = "
            SELECT oi.id, oi.order_id, oi.user_id, oi.user_hash, oi.product_id, oi.price_type_id, oi.count, oi.coupon_id, 
                   oi.discount, oi.price, oi.price_nds, oi.sum, oi.sum_nds, oi.price_discount, oi.price_discount_nds, 
                   oi.sum_discount_nds, oi.sum_discount, (oi.price - oi.price_discount) economy, 
                   (oi.sum - oi.sum_discount) sum_economy, oi.created, oi.updated, 
                   pt.name AS price_type, 
                   t.value AS tax,
                   p.name, p.preview_image, p.quantity,
                   u.sign AS unit,
                   curr.sign AS currency,
                   v.name AS vendor 
            FROM shop.order_items oi 
            LEFT JOIN shop.price_types pt 
                ON pt.id = oi.price_type_id 
            LEFT JOIN shop.products p 
                ON p.id = oi.product_id 
            LEFT JOIN shop.units u 
                ON u.id = p.unit_id
            LEFT JOIN shop.currencies curr
                ON p.currency_id = curr.id
            LEFT JOIN shop.taxes t 
                ON t.id = p.tax_id
            LEFT JOIN shop.vendors v 
                ON p.vendor_id = v.id
            WHERE oi.user_id = :user_id {$userHash} AND oi.order_id IS NULL AND oi.qorder_id IS NULL {$activity}";
        $db = Db::getInstance();
        $data = $db->query($sql, $params, $object ? static::class : null);
        return $data ?? false;
    }

    /**
     * Проверяет добавленный товар в корзину
     * @param int $product_id - id товара
     * @param int $count - количество товара
     * @param int $store_count - количество товара на складе
     * @return bool
     */
    public static function checkCartProduct(int $product_id, int $count, int $store_count)
    {
        return !empty($product_id) && !empty($count) && $store_count >= $count;
    }

    /**
     * Добавляет товар в корзину
     * @param ModelUser $user - пользователь
     * @param int $product_id - id товара
     * @param int $count - количество товара
     * @return bool|int
     */
    public static function add(ModelUser $user, int $product_id, int $count)
    {
        $price = ProductPrice::getPrice($product_id, $user->price_type_id);
        if (empty($price)) return false;

        $item = self::getProductByUser($product_id, $user->id, $_COOKIE['user']); // попытка найти этот товар в корзине пользователя
        if (empty($item->id)) { // товар в корзине не найден
            $item = new self();
            $item->user_id = $user->id;
            $item->user_hash = $_COOKIE['user'];
            $item->product_id = $product_id;
        }
        else $item->updated = date('Y-m-d H:i:s');

        $item->price_type_id = intval($user->price_type_id);
        $item->count = $count;
        $item->price = floatval($price->price);
        $item->sum = floatval($item->price * $item->count);

        if (!empty($price->tax_value)) { // НДС
            $item->tax = floatval($price->tax_value);
            $item->price_nds = round($item->price * $item->tax / (100 + $item->tax), 4);
            $item->sum_nds = round($item->sum * $item->tax / (100 + $item->tax), 4);
        }

        if (!empty($price->discount)) { // скидка
            $item->discount = floatval($price->discount);
            $item->price_discount = round($item->price * (100 - $item->discount) / 100);
            $item->sum_discount = $item->price_discount * $item->count;

            if (!empty($item->tax)) { // НДС
                $item->price_discount_nds = round($item->price_discount * $item->tax / (100 + $item->tax), 4);
                $item->sum_discount_nds = round($item->sum_discount * $item->tax / (100 + $item->tax), 4);
            }
        }

        return self::factory($item)->save();
    }

    /**
     * Удаляет товар из корзины
     * @param int $product_id - id товара
     * @param int $user_id - id пользователя
     * @return bool
     */
    public static function deleteItem(int $product_id, int $user_id)
    {
        $item = self::getProductByUser($product_id, $user_id, $_COOKIE['user']);
        if ($item) return $item->delete();

        return false;
    }

    /**
     * Очищает корзину
     * @param int $user_id - id пользователя
     * @param bool $isAjax - ajax запрос
     * @return bool
     */
    public static function clearCart(int $user_id, bool $isAjax = true)
    {
        $userHash = ($user_id === 2) ? 'AND user_hash = :user_hash' : '';
        $params = [':user_id' => $user_id ];
        if ($user_id === 2) $params[':user_hash'] = $_COOKIE['user'];
        $sql = "DELETE FROM order_items WHERE user_id = :user_id {$userHash} AND order_id IS NULL AND qorder_id IS NULL";
        $db = Db::getInstance();
        return $db->execute($sql, $params ?? []);
    }























































    /**
     * Пересчитывает корзину при изменении количества товара
     * @param ModelUser $user
     * @param int $product_id
     * @param int $count
     * @param bool $isAjax
     * @return array|bool
     * @throws DbException
     * @throws UserException
     */
    public static function recalc(ModelUser $user, int $product_id, int $count, bool $isAjax = false)
    {
        $item = self::getProductByUser($product_id, $user->id, $_COOKIE['cookie_hash']); // товар в корзине пользователя
        $cart = OrderItem::getCart($user->id); // корзина пользователя

        if ($cart && $item) { // получена актуальная корзина и добавленный товар
            $result = [
                'result'              => true,
                // цена товара и НДС
                'item_price'              => number_format($item->price, 0, '.', ' '),
                'item_price_nds'          => $item->price_nds ? number_format($item->price_nds, 2, '.', ' ') : null,
                // сумма товара и НДС
                'item_sum'                => number_format($item->sum, 0, '.', ' '),
                'item_sum_nds'            => $item->sum_nds ? number_format($item->sum_nds, 2, '.', ' ') : null,
                // цена товара со скидкой и НДС
                'item_price_discount'     => $item->price_discount ? number_format($item->price_discount, 0, '.', ' ') : null,
                'item_price_discount_nds' => $item->price_discount_nds ? number_format($item->price_discount_nds, 2, '.', ' ') : null,
                // сумма товара со скидкой и НДС
                'item_sum_discount'       => $item->sum_discount ? number_format($item->sum_discount, 0, '.', ' ') : null,
                'item_sum_discount_nds'   => $item->sum_discount_nds ? number_format($item->sum_discount_nds, 2, '.', ' ') : null,
                // экономия с цены и суммы
                'item_economy'            => $item->sum_discount ? number_format($item->economy, 0, '.', ' ') : null,
                'item_sum_economy'        => $item->sum_economy ? number_format($item->sum_economy, 0, '.', ' ') : null,
                // сумма корзины и НДС
                'cart_sum'                => $cart['sum'] ? number_format($cart['sum'], 0, '.', ' ') : null,
                'cart_sum_nds'            => $cart['sum_nds'] ? number_format($cart['sum_nds'], 0, '.', ' ') : null,
                // сумма корзина со скидкой и НДС
                'cart_sum_discount'       => $cart['sum_discount'] ? number_format($cart['sum_discount'], 0, '.', ' ') : null,
                'cart_sum_discount_nds'   => $cart['sum_discount_nds'] ? number_format($cart['sum_discount_nds'], 2, '.', ' ') : null,
                // экономия с корзины
                'cart_economy'            => $cart['economy'] ? number_format($cart['economy'], 0, '.', ' ') : null,
                // количество товаров в корзине
                'count'                   => $cart['count_items'],
                // сообщение
                'message'                 => $cart['message'],
            ];
        }

        return $result ?? false;
    }



























































    /**
     * Получает товар в корзине пользователя по его id
     * @param int $user_id
     * @param int $product_id
     * @param bool $object
     * @return false|mixed
     * @throws DbException
     */
    public static function getByUserId(int $user_id, int $product_id, $object = true)
    {
        $sql = "
            SELECT * 
            FROM order_items 
            WHERE user_id = :user_id AND product_id = :product_id AND order_id IS NULL AND qorder_id IS NULL";
        $params = [
            ':user_id' => $user_id,
            ':product_id' => $product_id
        ];
        $db = Db::getInstance();
        $data = $db->query($sql, $params, $object ? static::class : null);
        return !empty($data) ? array_shift($data) : false;
    }

    /**
     * Получает корзину анонимного пользователя
     * @param string $user_hash
     * @param bool $object
     * @return array|false
     * @throws DbException
     */
    public static function getAnonymous(string $user_hash, bool $object = true)
    {
        $sql = "
            SELECT * 
            FROM order_items 
            WHERE user_hash = :user_hash AND user_id = 2 AND order_id IS NULL AND qorder_id IS NULL";
        $params = [
            ':user_hash' => $user_hash
        ];
        $db = Db::getInstance();
        $data = $db->query($sql, $params, $object ? static::class : null);
        return $data ?? false;
    }

    /**
     * Проверка наличия корзины анонимного пользователя и привязка ее к авторизованному
     * @throws DbException
     */
    public static function checkAnonymous()
    {
        $items = self::getAnonymous($_COOKIE['user']);

        if (!empty($items) && is_array($items)) {
            $user = ModelUser::getCurrent();

            if (!empty($user->id)) {
                foreach ($items as $item) {
                    if (intval($user->id) !== intval($item->user_id)) {
                        $cart_item = self::getByUserId($user->id, $item->product_id);

                        if (!empty($cart_item->id)) { // в корзине авторизованного пользователя найден такой же товар, как и у анонимного
                            if (false === $cart_item->delete()) {
                                Logger::getInstance()->error(new DbException('Не удалось удалить анонимную корзину пользователя с id = ' . $user['id'] . ' и hash = ' . $_COOKIE['user']));
                            }
                        }

                        $item->user_id = $user->id;
                        $item->price_type_id = $user['price_type_id'];

                        if (false === $item->save()) {
                            Logger::getInstance()->error(new DbException('Не удалось привязать анонимную корзину пользователя с id = ' . $user['id'] . ' и hash = ' . $_COOKIE['user']));
                        }
                    }
                }
            }
        }
    }

    public function filter_id($id)
    {
        return (int)$id;
    }

    public function filter_order_id($id)
    {
        return (int)$id;
    }

    public function filter_user_id($id)
    {
        return (int)$id;
    }

    public function filter_user_hash($text)
    {
        return strip_tags(trim($text));
    }

    public function filter_product_id($id)
    {
        return (int)$id;
    }

    public function filter_count($id)
    {
        return (int)$id;
    }

    public function filter_price($text)
    {
        return strip_tags(trim($text));
    }
}
