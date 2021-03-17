<?php

namespace Models;

use Exceptions\UserException;
use System\Db;
use System\Logger;
use Exceptions\DbException;
use Exceptions\EditException;
use Exceptions\DeleteException;

class OrderItem extends Model
{
    protected static $table = 'order_items';
    public $id;                 // id записи
    public $order_id;           // id заказа
    public $qorder_id;          // id быстрого заказа
    public $user_id;            // id пользователя
    public $user_hash;          // hash пользователя
    public $product_id;         // id товара
    public $price_type_id;      // id типа цен
    public $count;              // количество
    public $coupon_id;          // id купона
    public $discount;           // скидка
    public $tax;                // размер налога
    public $price;              // цена
    public $price_nds;          // НДС цены
    public $price_discount;     // цена со скидкой
    public $price_discount_nds; // НДС цены со скидкой
    public $sum;                // сумма
    public $sum_nds;            // НДС суммы
    public $sum_discount;       // сумма со скидкой
    public $sum_discount_nds;   // НДС суммы со скидкой
    public $created;            // дата создания записи
    public $updated;            // дата создания записи

    /**
     * Получает актуальную корзину пользователя
     * @param int $user_id - id пользователя
     * @param string|null $coupon_code - код купона (этот функционал пока отложен на будущее...)
     * @return array|false
     * @throws DbException
     */
    public static function getCart(int $user_id, string $coupon_code = null)
    {
        $items = self::getListByUser($user_id, $_COOKIE['user']);
        //$coupon = $coupon_code ? Coupon::getByCodeUser($coupon_code, $user_id, $_COOKIE['user'], true) : null;

        if (!empty($items) && is_array($items)) {
            $count_items = 0;
            $sum = 0;
            $sum_nds = 0;
            $sum_discount = 0;
            $sum_discount_nds = 0;
            $absent = [];

            foreach ($items as $key => $item) {
                if ($item->count > $item->quantity) { // на складах недостаточно товара
                    $absent[] = $item;
                    unset($items[$key]);
                    continue;
                }

                $outdated = str_replace('-', '', explode(' ', $item->updated ?: $item->created)[0]) < date('Ymd');
                if ($outdated) { // товар в корзине больше суток
                    $message = 'Цены товаров, добавленных в корзину более суток назад обновлены';
                    $product = Product::getPriceItem($item->product_id, $item->price_type_id); // актуальные цены товара

                    $item->price = $product->price->price;
                    $item->price_nds = null;
                    $item->sum = $item->price * $item->count;
                    $item->sum_nds = null;
                    $item->updated = date('Y-m-d');
                    $item->discount = null;
                    $item->price_discount = null;
                    $item->price_discount_nds = null;
                    $item->sum_discount = null;
                    $item->sum_discount_nds = null;
                    $item->economy = null;

                    if (!empty($product->tax_value)) { // НДС
                        $item->tax = $product->tax_value;
                        $item->price_nds = round($item->price * $item->tax / (100 + $item->tax), 4);
                        $item->sum_nds = round($item->sum * $item->tax / (100 + $item->tax), 4);
                    }

                    if (!empty($product->discount)) { // скидка
                        $item->discount = $product->discount;
                        $item->price_discount = round($item->price * (100 - $item->discount) / 100);
                        $item->sum_discount = $item->price_discount * $item->count;
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
                'count_absent'     => count($absent),
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
     * Получает количество наименований товаров в корзине
     * @return false|mixed
     * @throws DbException
     */
    public static function getCount()
    {
        $user = User::getCurrent();
        $where = $user->id === '2' ? 'AND user_hash = :user_hash' : '';
        $params = [':user_id' => $user->id ];
        if ($user->id === '2') $params[':user_hash'] = $_COOKIE['user'];
        $sql = "
            SELECT count(*) AS count 
            FROM order_items 
            WHERE user_id = :user_id {$where} AND order_id IS NULL AND qorder_id IS NULL";

        $db = new Db();
        $data = $db->query($sql, $params ?? []);
        return !empty($data) ? array_shift($data)['count'] : false;
    }

    /**
     * Получает товар в корзине пользователя по его id и хэшу
     * @param int $product_id - id товара
     * @param int $user_id - id пользователя
     * @param string $user_hash - хэш пользователя (нужен для неавторизованного)
     * @param bool $object - возвращать объект/массив
     * @return false|mixed
     * @throws DbException
     */
    public static function getByUser(int $product_id, int $user_id, string $user_hash = '', $object = true)
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
            WHERE oi.user_id = :user_id AND oi.product_id = :product_id {$userHash} AND oi.order_id IS NULL";
        $db = new Db();
        $data = $db->query($sql, $params, $object ? static::class : null);
        return !empty($data) ? array_shift($data) : false;
    }

    /**
     * Получает список товаров в корзине по hash неавторизованного пользователя
     * @param int $user_id
     * @param string $user_hash
     * @param bool $active
     * @param bool $object
     * @return array|false
     * @throws DbException
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
            FROM order_items oi 
            LEFT JOIN price_types pt 
                ON pt.id = oi.price_type_id 
            LEFT JOIN products p 
                ON p.id = oi.product_id 
            LEFT JOIN units u 
                ON u.id = p.unit_id
            LEFT JOIN currencies curr
                ON p.currency_id = curr.id
            LEFT JOIN taxes t 
                ON t.id = p.tax_id
            LEFT JOIN vendors v 
                ON p.vendor_id = v.id
            WHERE oi.user_id = :user_id {$userHash} AND oi.order_id IS NULL AND oi.qorder_id IS NULL {$activity}";
        $db = new Db();
        $data = $db->query($sql, $params, $object ? static::class : null);
        return $data ?? false;
    }

    /**
     * Проверяет добавленный товар в корзину
     * @param Product $product - товар
     * @param int $product_id - id товара
     * @param int $count - количество товара
     * @return bool
     */
    public static function checkCartProduct(Product $product, int $product_id, int $count)
    {
        if (!empty($product_id) && !empty($count) && intval($product->quantity) >= $count)
            return true;

        return false;
    }

    /**
     * Добавляет товар в корзину
     * @param User $user - пользователь
     * @param int $product_id - id товара
     * @param int $count - количество товара
     * @return false|mixed
     * @throws DbException
     */
    public static function add(User $user, int $product_id, int $count)
    {
        $product = Product::getPriceItem($product_id, $user->price_type_id); // товар из каталога
        $item = self::getByUser($product_id, $user->id, $_COOKIE['user']); // попытка найти этот товар в корзине пользователя

        if (empty($item->id)) { // товар в корзине не найден
            $item = new self();
            $item->user_id = $user->id;
            $item->user_hash = $_COOKIE['user'];
            $item->product_id = $product_id;
        }

        $item->price_type_id = intval($user->price_type_id);
        $item->count = $count;
        $item->price = intval($product->price->price);
        $item->sum = $item->price * $count;
        $item->created = date('Y-m-d');

        if (!empty($product->tax_value)) { // НДС
            $item->tax = floatval($product->tax_value);
            $item->price_nds = round($item->price * $item->tax / (100 + $item->tax), 4);
            $item->sum_nds = round($item->sum * $item->tax / (100 + $item->tax), 4);
        }

        if (!empty($product->discount)) { // скидка
            $item->discount = floatval($product->discount);

            $item->price_discount = round($item->price * (100 - $product->discount) / 100);
            $item->sum_discount = $item->price_discount * $count;

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
     * @throws DbException
     */
    public static function deleteItem(int $product_id, int $user_id)
    {
        $item = self::getByUser($product_id, $user_id, $_COOKIE['user']);
        if ($item) return $item->delete();

        return false;
    }

    /**
     * Очищает корзину
     * @param int $user_id - id пользователя
     * @param bool $isAjax - ajax запрос
     * @return bool
     * @throws DbException
     */
    public static function clearCart(int $user_id, bool $isAjax = true)
    {
        $userHash = ($user_id === 2) ? 'AND user_hash = :user_hash' : '';
        $params = [':user_id' => $user_id ];
        if ($user_id === 2) $params[':user_hash'] = $_COOKIE['user'];
        $sql = "DELETE FROM order_items WHERE user_id = :user_id {$userHash} AND order_id IS NULL AND qorder_id IS NULL";
        $db = new Db();
        return $db->iquery($sql, $params ?? []);
    }























































    /**
     * Пересчитывает корзину при изменении количества товара
     * @param User $user
     * @param int $product_id
     * @param int $count
     * @param bool $isAjax
     * @return array|bool
     * @throws DbException
     * @throws UserException
     */
    public static function recalc(User $user, int $product_id, int $count, bool $isAjax = false)
    {
        $item = self::getByUser($product_id, $user->id, $_COOKIE['cookie_hash']); // товар в корзине пользователя
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
        $db = new Db();
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
        $db = new Db();
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
            $user = User::getCurrent();

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
