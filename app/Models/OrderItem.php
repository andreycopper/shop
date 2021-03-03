<?php

namespace Models;

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
        if ($user->id === '2') $params = [':user_hash' => $_COOKIE['user']];
        $sql = "
            SELECT count(*) AS count 
            FROM order_items 
            WHERE user_id = :user_id {$where} AND order_id IS NULL";

        $db = new Db();
        $data = $db->query($sql, $params ?? []);
        return !empty($data) ? array_shift($data)['count'] : false;
    }































    /**
     * Получает актуальную корзину пользователя
     * @param int $user_id
     * @param string $coupon_code
     * @return array|false
     * @throws DbException
     */
    public static function getCart(int $user_id, string $coupon_code = '')
    {
        if (!empty($user_id) && $user_id !== 2) {
            //$coupon = $coupon_code ? Coupon::getByCodeUserId($coupon_code, $user->id, true) : null;
            $items = self::getListByUserId($user_id);
        }
        else {
            //$coupon = $coupon_code ? Coupon::getByCodeUserHash($coupon_code, $_COOKIE['user'], true) : null;
            $items = self::getListByUserHash($_COOKIE['user']);
        }

        if (!empty($items) && is_array($items)) {
            $count_items = 0;
            $sum = 0;
            $sum_nds = 0;
            $discount_sum = 0;
            $discount_sum_nds = 0;
            $notavialable = [];

            foreach ($items as $key => $item) {
                if ($item->count > $item->quantity) { // на складах недостаточно товара
                    $notavialable[] = $item;
                    unset($items[$key]);
                    continue;
                }

                $outdated = str_replace('-', '', explode(' ', $item->created)[0]) < date('Ymd');
                if ($outdated) { // товар в корзине больше суток
                    if ($outdated) $message = 'Цены товаров, добавленных в корзину более суток назад обновлены';

                    $price = Product::getPrice($item->product_id, $item->price_type_id); // актуальные цены товара

                    $item->price = round($price->price * $price->rate);
                    $item->price_nds = null;
                    $item->sum = round($item->price * $item->count);
                    $item->sum_nds = null;
                    $item->created = date('Y-m-d');
                    $item->discount = null;
                    $item->discount_price = null;
                    $item->discount_price_nds = null;
                    $item->discount_sum = null;
                    $item->discount_sum_nds = null;
                    $item->economy = null;

                    if (!empty($price->tax)) { // НДС
                        $item->price_nds = $item->price * $price->tax / (100 + $price->tax);
                        $item->sum_nds = $item->sum * $price->tax / (100 + $price->tax);
                    }

                    if (!empty($price->discount)) { // скидка
                        $item->discount = $price->discount;
                        $item->discount_price = round($item->price * (100 - $price->discount) / 100);
                        $item->discount_sum = round($item->discount_price * $item->count);
                        $item->economy = round($item->price - $item->discount_price);

                        if (!empty($price->tax)) { // НДС
                            $item->discount_price_nds = $item->discount_price * $price->tax / (100 + $price->tax);
                            $item->discount_sum_nds = $item->discount_sum * $price->tax / (100 + $price->tax);
                        }
                    }

                    if (!self::factory($item)->save()) Logger::getInstance()->error(new EditException('Не удалось обновить в корзине товар id=' . $item->id));
                }

                $count_items += $item->count;
                $sum += $item->sum;
                $sum_nds += $item->sum_nds;
                $discount_sum += $item->discount_sum ?? $item->sum;
                $discount_sum_nds += $item->discount_sum_nds ?? $item->sum_nds;
            }

            $result = [
                'items'              => $items,
                'notavialable'       => $notavialable,
                'count_items'        => $count_items,
                'count_notavialable' => count($notavialable),
                'sum'                => $sum,
                'sum_nds'            => $sum_nds,
                'discount_sum'       => $discount_sum,
                'discount_sum_nds'   => $discount_sum_nds,
                'economy'            => $sum - $discount_sum,
                'coupon'             => $coupon ?? null,
                'message'            => $message ?? ''
            ];
        }
        return $result ?? false;
    }

    /**
     * Получает список товаров в корзине по id пользователя
     * @param int $user_id
     * @param bool $active
     * @param bool $object
     * @return array|false
     * @throws DbException
     */
    public static function getListByUserId(int $user_id, bool $active = false, bool $object = true)
    {
        $activity = !empty($active) ? 'AND p.active IS NOT NULL' : '';
        $params = [
            ':user_id' => $user_id
        ];
        $sql = "
            SELECT oi.id, oi.order_id, oi.user_id, oi.user_hash, oi.product_id, oi.price_type_id, oi.count, oi.coupon_id, 
                   oi.discount, oi.price, oi.price_nds, oi.sum, oi.sum_nds, oi.discount_price, oi.discount_price_nds, 
                   oi.discount_sum_nds, oi.discount_sum, (oi.price - oi.discount_price) economy, oi.created, 
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
            WHERE oi.user_id = :user_id AND oi.order_id IS NULL {$activity}
            ";
        $db = new Db();
        $data = $db->query($sql, $params, $object ? static::class : null);
        return $data ?? false;
    }

    /**
     * Получает список товаров в корзине по hash неавторизованного пользователя
     * @param int $user_hash
     * @param bool $active
     * @param bool $object
     * @return array|false
     * @throws DbException
     */
    public static function getListByUserHash(int $user_hash, bool $active = false, bool $object = true)
    {
        $activity = !empty($active) ? 'AND p.active IS NOT NULL' : '';
        $params = [
            ':user_hash' => $user_hash
        ];
        $sql = "
            SELECT oi.id, oi.order_id, oi.user_id, oi.user_hash, oi.product_id, oi.price_type_id, oi.count, oi.coupon_id, 
                   oi.discount, oi.price, oi.price_nds, oi.sum, oi.sum_nds, oi.discount_price, oi.discount_price_nds, 
                   oi.discount_sum_nds, oi.discount_sum, (oi.price - oi.discount_price) economy, oi.created, 
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
            WHERE oi.user_id = 2 AND oi.user_hash = :user_hash AND oi.order_id IS NULL {$activity}";
        $db = new Db();
        $data = $db->query($sql, $params, $object ? static::class : null);
        return $data ?? false;
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
            WHERE user_id = :user_id AND product_id = :product_id AND order_id IS NULL";
        $params = [
            ':user_id' => $user_id,
            ':product_id' => $product_id
        ];
        $db = new Db();
        $data = $db->query($sql, $params, $object ? static::class : null);
        return !empty($data) ? array_shift($data) : false;
    }

    /**
     * Получает товар в корзине пользователя по его хэшу
     * @param string $user_hash
     * @param int $product_id
     * @param bool $object
     * @return false|mixed
     * @throws DbException
     */
    public static function getByUserHash(string $user_hash, int $product_id, $object = true)
    {
        $sql = "
            SELECT * 
            FROM order_items 
            WHERE user_id = 2 AND user_hash = :user_hash AND product_id = :id AND order_id IS NULL";
        $params = [
            ':user_hash' => $user_hash,
            ':id' => $product_id
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
            WHERE user_hash = :user_hash AND user_id = 2 AND order_id IS NULL";
        $params = [
            ':user_hash' => $user_hash
        ];
        $db = new Db();
        $data = $db->query($sql, $params, $object ? static::class : null);
        return $data ?? false;
    }

    /**
     * Добавляет товар в корзину (ajax)
     * @param int $product_id
     * @param int $count
     * @param int $price_type
     * @param bool $isAjax
     * @return OrderItem|false|mixed
     * @throws DbException
     */
    public static function add(int $product_id, int $count, int $price_type, bool $isAjax = false)
    {
        if (!empty($product_id)) {
            if (!empty($count)) {
                $product = Product::getByIdWithRate($product_id, $price_type, true);

                if (!empty($product->id)) {
                    if (intval($product->quantity) >= $count) {

                        $user = User::getCurrent();

                        if (!empty($user->id)) $item = self::getByUserId($user->id, $product_id);
                        else $item = self::getByUserHash($_COOKIE['user'], $product_id);

                        if (empty($item->id)) {
                            $item = new self();
                            $item->user_id = $user->id; // если не авторизован, пишем корзину на id = 2 (user)
                            $item->user_hash = $_COOKIE['user'];
                            $item->product_id = $product_id;
                        }

                        $item->price_type_id = $price_type;
                        $item->count = $count;
                        $item->price = round($product->price * $product->rate);
                        $item->sum = round($item->price * $count);
                        $item->created = date('Y-m-d');

                        if (!empty($product->tax)) { // НДС
                            $item->tax = $product->tax;
                            $item->price_nds = round($item->price * $product->tax / (100 + $product->tax));
                            $item->sum_nds = round($item->sum * $product->tax / (100 + $product->tax));
                        }

                        if (!empty($product->discount)) { // скидка
                            $item->discount = $product->discount;
                            $item->discount_price = round($product->price * $product->rate * (100 - $product->discount) / 100);
                            $item->discount_sum = round($item->discount_price * $count);

                            if (!empty($product->tax)) { // НДС
                                $item->discount_price_nds = round($item->discount_price * $product->tax / (100 + $product->tax));
                                $item->discount_sum_nds = round($item->discount_sum * $product->tax / (100 + $product->tax));
                            }
                        }

                        if ($item->save()) {
                            if ($isAjax) {
                                echo json_encode([
                                    'result' => true,
                                    'count' => self::getCount()
                                ]);
                                die;
                            } else return $item;
                        } else $message = 'Не удалось сохранить в корзину ' . $product->name . ' в количестве ' . $count;
                    } else $message = 'На складе товаров меньше указанного количества';
                } else $message = 'Товар не найден';
            } else $message = 'Указано неверное количество товара';
        } else $message = 'Не указан товар';

        Logger::getInstance()->error(new EditException($message));
        echo json_encode([
            'result' => false,
            'message' => $message
        ]);
        die;
    }

    /**
     * Пересчитывает корзину при изменении количества товара
     * @param int $product_id
     * @param int $count
     * @param int $price_type
     * @param bool $isAjax
     * @return array
     * @throws DbException
     */
    public static function recalc(int $user_id, int $product_id, int $count, int $price_type, bool $isAjax = false)
    {
        $item = OrderItem::add($product_id, $count, $price_type);

        if ($item) { // товар сохранен в корзине
            $cart = OrderItem::getCart($user_id);

            if ($cart) { // получена актуальная корзина
                $result = [
                    'result'              => true,
                    'item_price'          => number_format($item->price, 0, '.', ' '),
                    'item_sum'            => number_format($item->sum, 0, '.', ' '),
                    'item_discount_price' => $item->discount_price ? number_format($item->discount_price, 0, '.', ' ') : null,
                    'item_discount_sum'   => $item->discount_sum ? number_format($item->discount_sum, 0, '.', ' ') : null,
                    'item_economy'        => $item->discount_sum ? number_format(($item->sum - $item->discount_sum), 0, '.', ' ') : null,
                    'cart_sum'            => $cart['sum'],
                    'cart_discount_sum'   => $cart['discount_sum'],
                    'cart_economy'        => $cart['economy'],
                    'message'             => $cart['message'],
                ];

                if ($isAjax) {
                    echo json_encode($result);
                    die;
                } else return $result;
            } else $message = 'Не удалось обновить корзину';
        } else $message = 'Не удалось сохранить корзину';

        $result = [
            'result' => false,
            'message' => $message
        ];

        if ($isAjax) {
            echo json_encode($result);
            die;
        } else return $result;
    }

    /**
     * Удаляет товар из корзины
     * @param int $product_id
     * @return array
     * @throws DbException
     */
    public static function deleteItem(int $product_id)
    {
        $user = User::getCurrent();

        if (!empty($user->id)) $item = OrderItem::getByUserId($user->id, $product_id);
        else $item = OrderItem::getByUserHash($_COOKIE['user'], $product_id);

        if ($item) { // товар найден в корзине
            if ($item->delete()) { // удален товар из корзины
                $cart = OrderItem::getCart();

                if ($cart) { // получена актуальная корзина
                    return [
                        'result'              => true,
                        'count'               => $cart['count_items'],
                        'cart_sum'            => $cart['sum'],
                        'cart_discount_sum'   => $cart['discount_sum'],
                        'cart_economy'        => $cart['economy'],
                        'message'             => $cart['message'],
                    ];
                } else $message = 'Не удалось обновить корзину';
            } else $message = 'Не удалось удалить товар из корзины';
        } else $message = 'Не удалось найти товар в корзине';

        Logger::getInstance()->error(new DeleteException($message));
        return [
            'result' => false,
            'message' => $message
        ];
    }

    /**
     * Очищает корзину
     * @throws DbException
     */
    public static function clearCart()
    {
        $user = User::getCurrent();

        if (!empty($user->id)) $items = self::getListByUserId($user->id);
        else $items = self::getListByUserHash($_COOKIE['user']);

        if (!empty($items) && is_array($items)) { // найдены товары в корзине
            foreach ($items as $key => $item) {
                if (!$item->delete()) { // не удалось удалить товар
                    $message = 'Не удалось удалить товар с id=' . $item->product_id . ' пользователя с id=' . $item->user_id;
                    Logger::getInstance()->error(new DeleteException($message));

                    return [
                        'result' => false,
                        'message' => $message
                    ];
                }
            }
            return ['result' => true];
        } else $message = 'Не найдены товары в корзине';

        Logger::getInstance()->error(new DeleteException($message));

        return [
            'result' => false,
            'message' => $message
        ];
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
