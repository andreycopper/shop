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
    public $sum;                // сумма
    public $sum_nds;            // НДС суммы
    public $discount_price;     // цена со скидкой
    public $discount_price_nds; // НДС цены со скидкой
    public $discount_sum;       // сумма со скидкой
    public $discount_sum_nds;   // НДС суммы со скидкой
    public $created;            // дата создания записи
    public $updated;            // дата создания записи

    /**
     * Получает количество товаров в корзине
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
                   oi.discount, oi.price, oi.price_nds, oi.sum, oi.sum_nds, oi.discount_price, oi.discount_price_nds, 
                   oi.discount_sum_nds, oi.discount_sum, (oi.price - oi.discount_price) economy, 
                   (oi.sum - oi.discount_sum) sum_economy, oi.created, oi.updated 
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
                   oi.discount, oi.price, oi.price_nds, oi.sum, oi.sum_nds, oi.discount_price, oi.discount_price_nds, 
                   oi.discount_sum_nds, oi.discount_sum, (oi.price - oi.discount_price) economy, 
                   (oi.sum - oi.discount_sum) sum_economy, oi.created, oi.updated, 
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
     * @param $product
     * @param int $product_id
     * @param int $count
     * @param $isAjax
     * @return bool
     * @throws UserException
     */
    public static function checkCartProduct($product, int $product_id, int $count, $isAjax)
    {
        if (!empty($product_id)) {
            if (!empty($count)) {
                if (!empty($product->id)) {
                    if (intval($product->quantity) >= $count) {
                        return true;
                    } else $message = 'На складе товаров меньше указанного количества';
                } else $message = 'Товар не найден';
            } else $message = 'Указано неверное количество товара';
        } else $message = 'Не указан товар';

        self::returnError($message, $isAjax);
        return false;
    }

    /**
     * Добавляет товар в корзину
     * @param User $user
     * @param int $product_id
     * @param int $count
     * @param bool $isAjax
     * @return false|mixed
     * @throws DbException
     * @throws UserException
     */
    public static function add(User $user, int $product_id, int $count, bool $isAjax = false)
    {
        $product = Product::getPriceItem($product_id, $user->price_type_id);

        if (self::checkCartProduct($product, $product_id, $count, $isAjax)) { // проверка товара, количества и его наличия на складе
            $item = self::getByUser($product_id, $user->id, $_COOKIE['user']);

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

                $item->discount_price = round($item->price * (100 - $product->discount) / 100);
                $item->discount_sum = $item->discount_price * $count;

                if (!empty($item->tax)) { // НДС
                    $item->discount_price_nds = round($item->discount_price * $item->tax / (100 + $item->tax), 4);
                    $item->discount_sum_nds = round($item->discount_sum * $item->tax / (100 + $item->tax), 4);
                }
            }

            return self::factory($item)->save();

            if (!self::factory($item)->save()) self::returnError('Не удалось сохранить в корзину ' . $product->name . ' в количестве ' . $count, $isAjax);
            else {
                if ($isAjax) {
                    echo json_encode([
                        'result' => true,
                        'count' => self::getCount()
                    ]);
                    die;
                } else return true;
            }
        }
        return false;
    }

    /**
     * Удаляет товар из корзины
     * @param int $product_id - id товара
     * @param int $user_id - id пользователя
     * @param bool $isAjax - ajax запрос
     * @return bool
     * @throws DbException
     * @throws UserException
     */
    public static function deleteItem(int $product_id, int $user_id, bool $isAjax = true)
    {
        $item = self::getByUser($product_id, $user_id, $_COOKIE['user']);

        if ($item) { // товар найден в корзине
            if ($item->delete()) { // удален товар из корзины
                return self::returnSuccess($isAjax);
            } else $message = 'Не удалось удалить товар из корзины';
        } else $message = 'Не удалось найти товар в корзине';

        self::returnError($message, $isAjax);
    }

    /**
     * Очищает корзину
     * @param int $user_id - id пользователя
     * @param bool $isAjax - ajax запрос
     * @return bool
     * @throws DbException
     * @throws UserException
     */
    public static function clearCart(int $user_id, bool $isAjax = true)
    {
        $userHash = ($user_id === 2) ? 'AND user_hash = :user_hash' : '';
        $params = [':user_id' => $user_id ];
        if ($user_id === 2) $params[':user_hash'] = $_COOKIE['user'];
        $sql = "DELETE FROM order_items WHERE user_id = :user_id {$userHash} AND order_id IS NULL AND qorder_id IS NULL";
        $db = new Db();
        $data = $db->iquery($sql, $params ?? []);

        if ($data) self::returnSuccess($isAjax);
        else self::returnError('Не удалось очистить корзину пользователя id=' . $user_id, $isAjax);
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
        if (self::add($user, $product_id, $count)) { // товар сохранен в корзине
            $item = self::getByUser($product_id, $user->id, $_COOKIE['cookie_hash']);
            $cart = OrderItem::getCart($user->id);

            if ($cart) { // получена актуальная корзина
                $result = [
                    'result'              => true,
                    // цена товара и НДС
                    'item_price'              => number_format($item->price, 0, '.', ' '),
                    'item_price_nds'          => $item->price_nds ? number_format($item->price_nds, 2, '.', ' ') : null,
                    // сумма товара и НДС
                    'item_sum'                => number_format($item->sum, 0, '.', ' '),
                    'item_sum_nds'            => $item->sum_nds ? number_format($item->sum_nds, 2, '.', ' ') : null,
                    // цена товара со скидкой и НДС
                    'item_discount_price'     => $item->discount_price ? number_format($item->discount_price, 0, '.', ' ') : null,
                    'item_discount_price_nds' => $item->discount_price_nds ? number_format($item->discount_price_nds, 2, '.', ' ') : null,
                    // сумма товара со скидкой и НДС
                    'item_discount_sum'       => $item->discount_sum ? number_format($item->discount_sum, 0, '.', ' ') : null,
                    'item_discount_sum_nds'   => $item->discount_sum_nds ? number_format($item->discount_sum_nds, 2, '.', ' ') : null,
                    // экономия с цены и суммы
                    'item_economy'            => $item->discount_sum ? number_format($item->economy, 0, '.', ' ') : null,
                    'item_sum_economy'        => $item->sum_economy ? number_format($item->sum_economy, 0, '.', ' ') : null,
                    // сумма корзины и НДС
                    'cart_sum'                => $cart['sum'] ? number_format($cart['sum'], 0, '.', ' ') : null,
                    'cart_sum_nds'            => $cart['sum_nds'] ? number_format($cart['sum_nds'], 0, '.', ' ') : null,
                    // сумма корзина со скидкой и НДС
                    'cart_discount_sum'       => $cart['discount_sum'] ? number_format($cart['discount_sum'], 0, '.', ' ') : null,
                    'cart_discount_sum_nds'   => $cart['discount_sum_nds'] ? number_format($cart['discount_sum_nds'], 2, '.', ' ') : null,
                    // экономия с корзины
                    'cart_economy'            => $cart['economy'] ? number_format($cart['economy'], 0, '.', ' ') : null,
                    // количество товаров в корзине
                    'count'                   => $cart['count_items'],
                    // сообщение
                    'message'                 => $cart['message'],
                ];

                if ($isAjax) {
                    echo json_encode($result);
                    die;
                } else return true;
            } else $message = 'Не удалось обновить корзину';
        } else $message = 'Не удалось сохранить корзину';

        self::returnError($message, $isAjax);
        return false;
    }

    /**
     * Получает актуальную корзину пользователя
     * @param int $user_id
     * @param string|null $coupon_code
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
            $discount_sum = 0;
            $discount_sum_nds = 0;
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
                    $item->discount_price = null;
                    $item->discount_price_nds = null;
                    $item->discount_sum = null;
                    $item->discount_sum_nds = null;
                    $item->economy = null;

                    if (!empty($product->tax_value)) { // НДС
                        $item->tax = $product->tax_value;
                        $item->price_nds = round($item->price * $item->tax / (100 + $item->tax), 4);
                        $item->sum_nds = round($item->sum * $item->tax / (100 + $item->tax), 4);
                    }

                    if (!empty($product->discount)) { // скидка
                        $item->discount = $product->discount;
                        $item->discount_price = round($item->price * (100 - $item->discount) / 100);
                        $item->discount_sum = $item->discount_price * $item->count;
                        $item->economy = $item->price - $item->discount_price;
                        $item->sum_economy = $item->sum - $item->discount_sum;

                        if (!empty($item->tax)) { // НДС
                            $item->discount_price_nds = round($item->discount_price * $item->tax / (100 + $item->tax), 4);
                            $item->discount_sum_nds = round($item->discount_sum * $item->tax / (100 + $item->tax), 4);
                        }
                    }

                    if (!self::factory($item)->save()) Logger::getInstance()->error(new EditException('Не удалось обновить в корзине товар id=' . $item->id));
                }

                $count_items += $item->count;
                $sum += $item->sum;
                $sum_nds += $item->sum_nds ?: 0;
                $discount_sum += $item->discount_sum ?: $item->sum;
                $discount_sum_nds += $item->discount_sum_nds ?: $item->sum_nds;
            }

            $result = [
                'items'            => $items,
                'absent'           => $absent,
                'count_items'      => $count_items,
                'count_absent'     => count($absent),
                'sum'              => $sum,
                'sum_nds'          => $sum_nds,
                'discount_sum'     => $discount_sum,
                'discount_sum_nds' => $discount_sum_nds,
                'economy'          => $sum - $discount_sum,
                'coupon'           => $coupon ?? null,
                'message'          => $message ?? ''
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
